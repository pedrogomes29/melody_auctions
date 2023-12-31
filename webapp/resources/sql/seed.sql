create schema if not exists lbaw2211;

-- ----------------------------------------------------------
-- CREATE TABLES
-- ----------------------------------------------------------

SET search_path TO lbaw2211;

Drop table if exists admins cascade;

CREATE TABLE admins (
	id serial4 NOT NULL,
	firstname varchar(20) NOT NULL,
	lastname varchar(20) NOT NULL,
	username varchar(25) NOT NULL,
	"password" varchar(100) NOT NULL,
	email varchar(30) NOT NULL,
	photo varchar(100) NULL,
	description varchar(200) NULL,
	contact varchar(15) NULL,
	CONSTRAINT admin_email_key UNIQUE (email),
	CONSTRAINT admin_pkey PRIMARY KEY (id),
	CONSTRAINT admin_username_key UNIQUE (username)
);


Drop TABLE if exists authenticated_users cascade;

CREATE TABLE authenticated_users (
	id serial4 NOT NULL,
	firstname varchar(20) NULL,
	lastname varchar(20) NULL,
	username varchar(25) NULL,
	"password" varchar(100) NULL,
	email varchar(30) NULL,
	photo varchar(100) DEFAULT '',
	description varchar(200) NULL,
	contact varchar(15) NULL,
	balance int NULL,
	tsvectors tsvector NULL,
	remember_token varchar(100) NULL,
	deleted_at timestamp NULL,
	CONSTRAINT authenticateduser_balance_check CHECK ((balance >= 0)),
	CONSTRAINT authenticateduser_pkey PRIMARY KEY (id)
);
CREATE INDEX user_search_idx ON authenticated_users USING gist (tsvectors);

Drop TABLE if exists categories cascade;

CREATE TABLE categories (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	photo varchar(100) NULL,
	CONSTRAINT categories_name_key UNIQUE (name),
	CONSTRAINT categories_pkey PRIMARY KEY (id)
);


Drop TABLE if exists manufactors cascade;

CREATE TABLE manufactors (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	CONSTRAINT manufactors_name_key UNIQUE (name),
	CONSTRAINT manufactors_pkey PRIMARY KEY (id)
);



Drop TABLE if exists notifications cascade;

CREATE TABLE notifications (
	id serial4 NOT NULL,
	"date" timestamp NOT NULL,
	beenread bool NULL DEFAULT false,
	CONSTRAINT notifications_pkey PRIMARY KEY (id)
);

Drop TABLE if exists authenticated_user_notification cascade;

CREATE TABLE authenticated_user_notification(
	authenticated_user_id int4 NOT NULL,
	notification_id int4 NOT NULL,
	CONSTRAINT authenticated_user_notification_authenticated_user_id_fkey FOREIGN KEY (authenticated_user_id) REFERENCES authenticated_users,
	CONSTRAINT authenticated_user_notification_notification_id_fkey FOREIGN KEY (notification_id) REFERENCES notifications,
	CONSTRAINT authenticated_user_notification_pkey PRIMARY KEY (authenticated_user_id, notification_id)
);


Drop TABLE if exists reports_states cascade;

CREATE TABLE reports_states (
	id serial4 NOT NULL,
	state varchar(50) NOT NULL,
	CONSTRAINT reportsstate_pkey PRIMARY KEY (id),
	CONSTRAINT reportsstate_state_key UNIQUE (state)
);


Drop TABLE if exists auctions cascade;

CREATE TABLE auctions (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	startdate timestamp NOT NULL,
	startprice float8 NOT NULL,
	currentprice float8 NULL,
	lastbidsdate timestamp NULL,
	minbidsdif float8 NULL,
	photo varchar(100) NULL,
	description text NULL,
	owner_id int4 NOT NULL,
	category_id int4 NOT NULL,
	manufactor_id int4 NOT NULL,
	enddate timestamp NOT NULL,
	winner_id int4 NULL,
	tsvectors tsvector NULL,
	cancelled bool NOT NULL default false,
	already_notified_ending bool NOT NULL default false,
	already_notified_ended bool NOT NULL default false,
	CONSTRAINT auctions_check CHECK ((currentprice >= startprice)),
	CONSTRAINT auctions_check1 CHECK ((lastbidsdate >= startdate)),
	CONSTRAINT auctions_check2 CHECK ((enddate >= startdate)),
	CONSTRAINT auctions_minbidsdif_check CHECK ((minbidsdif >= (0)::double precision)),
	CONSTRAINT auctions_pkey PRIMARY KEY (id),
	CONSTRAINT auctions_startprice_check CHECK ((startprice >= (0)::double precision)),
	CONSTRAINT auctions_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id),
	CONSTRAINT auctions_manufactor_id_fkey FOREIGN KEY (manufactor_id) REFERENCES manufactors(id),
	CONSTRAINT auctions_owner__idfkey FOREIGN KEY (owner_id) REFERENCES authenticated_users
(id),
	CONSTRAINT auctions_winner_id_fkey FOREIGN KEY (winner_id) REFERENCES authenticated_users
(id)
);
CREATE INDEX auctions_price ON auctions USING btree (currentprice);
CREATE INDEX auctions_search_idx ON auctions USING gist (tsvectors);


Drop TABLE if exists auctions_cancelled_notifications cascade;

CREATE TABLE auctions_cancelled_notifications (
	notification_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	CONSTRAINT auctionscancellednotifications_pkey PRIMARY KEY (notification_id),
	CONSTRAINT auctionscancellednotifications_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT auctionscancellednotifications_notification_id_fkey FOREIGN KEY (notification_id) REFERENCES notifications
(id)
);

Drop TABLE if exists auctions_ended_notifications cascade;

CREATE TABLE auctions_ended_notifications (
	notification_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	CONSTRAINT auctionsendednotifications_pkey PRIMARY KEY (notification_id),
	CONSTRAINT auctionsendednotifications_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT auctionsendednotifications_notification_id_fkey FOREIGN KEY (notification_id) REFERENCES notifications
(id)
);


Drop TABLE if exists auctions_ending_notifications cascade;

CREATE TABLE auctions_ending_notifications (
	notification_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	CONSTRAINT auctionsendingnotifications_pkey PRIMARY KEY (notification_id),
	CONSTRAINT auctionsendingnotifications_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT auctionsendingnotifications_notification_id_fkey FOREIGN KEY (notification_id) REFERENCES notifications
(id)
);


Drop TABLE if exists bids cascade;

CREATE TABLE bids (
	id serial4 NOT NULL,
	authenticated_user_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	value float8 NOT NULL,
	bidsdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT bids_pkey PRIMARY KEY (id),
	CONSTRAINT bids_value_check CHECK ((value >= (0)::double precision)),
	CONSTRAINT bids_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT bids_authenticated_user_id_fkey FOREIGN KEY (authenticated_user_id) REFERENCES authenticated_users
(id)
);


Drop TABLE if exists bids_notifications cascade;

CREATE TABLE bids_notifications (
	notification_id int4 NOT NULL,
	bid_id int4 NOT NULL,
	CONSTRAINT bidsnotifications_pkey PRIMARY KEY (notification_id),
	CONSTRAINT bidsnotifications_bid_id_fkey FOREIGN KEY (bid_id) REFERENCES bids(id),
	CONSTRAINT bidsnotifications_notification_id_fkey FOREIGN KEY (notification_id) REFERENCES notifications(id)
);


Drop TABLE if exists follows cascade;

CREATE TABLE follows (
	authenticated_user_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	CONSTRAINT follows_pkey PRIMARY KEY (authenticated_user_id, auction_id),
	CONSTRAINT follows_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT follows_authenticated_user_id_fkey FOREIGN KEY (authenticated_user_id) REFERENCES authenticated_users
(id)
);

Drop TABLE if exists messages cascade;

CREATE TABLE messages (
	id serial4 NOT NULL,
	authenticated_user_id int4 NOT NULL,
	auction_id int4 NOT NULL,
	"text" text NOT NULL,
	"date" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT messages_pkey PRIMARY KEY (id),
	CONSTRAINT messages_auction_id_fkey FOREIGN KEY (auction_id) REFERENCES auctions(id),
	CONSTRAINT messages_authenticated_user_id_fkey FOREIGN KEY (authenticated_user_id) REFERENCES authenticated_users
(id)
);


Drop TABLE if exists reports cascade;

CREATE TABLE reports(
	id serial4 NOT NULL,
	reportstext varchar(500) NOT NULL,
	reportsdate date NOT NULL,
	reports_state_id int4 NOT NULL,
	reported_id int4 NOT NULL,
	reporter_id int4 NOT NULL,
	CONSTRAINT reports_pkey PRIMARY KEY (id),
	CONSTRAINT reports_reported_id_fkey FOREIGN KEY (reported_id) REFERENCES authenticated_users
(id),
	CONSTRAINT reports_reporter_id_fkey FOREIGN KEY (reporter_id) REFERENCES authenticated_users
(id),
	CONSTRAINT reports_reports_state_id_fkey FOREIGN KEY (reports_state_id) REFERENCES reports_states(id)
);


Drop TABLE if exists reviews cascade;

CREATE TABLE reviews (
	id serial4 NOT NULL,
	reviewer_id int4 NOT NULL,
	reviewed_id int4 NOT NULL,
	reviewsdate date NOT NULL DEFAULT CURRENT_DATE,
	"comment" varchar(250) NULL,
	rating int4 NOT NULL,
	CONSTRAINT reviews_pkey PRIMARY KEY (id),
	CONSTRAINT reviews_rating_check CHECK (((0 < rating) AND (rating <= 5))),
	CONSTRAINT reviews_reviewed_id_fkey FOREIGN KEY (reviewed_id) REFERENCES authenticated_users
(id),
	CONSTRAINT reviews_reviewer_id_fkey FOREIGN KEY (reviewer_id) REFERENCES authenticated_users
(id)
);

DROP TABLE IF EXISTS password_resets;

CREATE TABLE password_resets(
	email varchar(30),
	token text,
	created_at timestamp NULL
);

CREATE INDEX password_reset_email ON password_resets USING hash (email);
CREATE INDEX password_reset_token ON password_resets USING hash (token);


-- ----------------------------------------------------------
-- CREATE FUNCTIONS
-- ----------------------------------------------------------


CREATE OR REPLACE FUNCTION auctions_search_update()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.name), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.name), 'A') ||
             setweight(to_tsvector('english', NEW.description), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $function$
;

CREATE OR REPLACE FUNCTION bids_already_won()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		if EXISTS (select * from auctions where new.auction_id = id and winner_id is not null) then 
		raise exception 'Cannot bids on an auctions already won';
		end if;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION delete_auctions()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
	if exists(select * from bids where auction_id = old.id) then 
			RAISE EXCEPTION 'Cannot erase an auctions that people have bidsded on';
		end if;
		return old;
	END;
$function$
;

CREATE OR REPLACE FUNCTION min_bids_diff()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE mindiff float8;
		last_price float8;
		startprice float8;

begin
		
		select auctions.startprice, auctions.currentprice, auctions.minbidsdif  from auctions where NEW.auction_id = auctions.id into startprice, last_price, mindiff;
		
			
		if  startprice > NEW.value THEN
           RAISE EXCEPTION 'The bids value must be greater than %.', startprice;
        elsif last_price is not null and  last_price + mindiff > NEW.value THEN
           RAISE EXCEPTION 'The bids value must be greater than % ', last_price+mindiff;
        END IF;
        RETURN NEW;
END
$function$
;

CREATE OR REPLACE FUNCTION must_have_balance()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		if EXISTS (select * from authenticated_users
	 where new.authenticated_user_id = id and new.value > balance) then 
		raise exception 'Insuficient balance to bids that value';
		end if;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION one_reports()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
	IF EXISTS (SELECT * FROM reports as R WHERE NEW.reporter_id = R.reporter_id and R.reported_id = new.reported_id) THEN
           RAISE EXCEPTION 'Cant reports a user more than once';
        END IF;
        RETURN NEW;
	END;
$function$
;

CREATE OR REPLACE FUNCTION owner_auctions()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
        IF EXISTS (SELECT * FROM auctions as A WHERE NEW.authenticated_user_id = a.owner_id and a.id = new.auction_id) THEN
           RAISE EXCEPTION 'The owner of an auctions cannot bids in its own auctions';
        END IF;
        RETURN NEW;
	END;
$function$
;

CREATE OR REPLACE FUNCTION same_bidder()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		if (select authenticated_user_id  from bids where new.auction_id = auction_id order by value desc limit 1) = new.authenticated_user_id then 
		raise exception 'Cannot bids on an auctions that you are the last person to bids';
		end if;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION update_balance()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE 
		last_price float8;
		last_bidder int4;

begin
		
		select authenticated_user_id , value  from bids  where NEW.auction_id = bids.auction_id and new.id != bids.id order by value desc
				into last_bidder, last_price;
			
		if last_bidder is not null then
			update authenticated_users
		 set balance = (balance+last_price) where id=last_bidder;
			
		end if;
		update authenticated_users
	 set balance = (balance-new.value) where id=new.authenticated_user_id;
        RETURN NEW;
END
$function$
;

CREATE OR REPLACE FUNCTION update_current_price()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		update auctions set currentprice = new.value, lastbidsdate =new.bidsdate where auctions.id = new.auction_id;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION update_end_date()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
	UPDATE auctions
	SET enddate = date_trunc('minute',CURRENT_TIMESTAMP + (30 * interval '1 minute'))
	WHERE auctions.id=NEW.auction_id AND enddate-current_timestamp < (15 * interval '1 minute') ;
	RETURN NEW;
END $function$
;

CREATE OR REPLACE FUNCTION update_owner_balance()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
declare  
	winner int4;
	winvalue float8;
	last_bidder int4;
begin
	
		if (NEW.winner_id IS NULL OR NEW.winner_id=OLD.winner_id) then
			return new;
		end if;
		
		select winner_id, currentprice from auctions  where auctions.id = new.id into winner, winvalue;
		
		if winvalue is null then
			raise exception 'Current Price is null';
		elsif winner is not null then
			raise exception 'auctions already has a winner';
		else
			select bids.authenticated_user_id  from bids where bids.auction_id = new.id and bids.value = winvalue into last_bidder;
			if last_bidder is not null then
				update authenticated_users
			  set balance  = (balance+ winvalue)
						where id=new.owner_id;
			else
				raise exception 'auctions already has a winner';
			end if;	
		end if;
	
	
        RETURN NEW;
END
$function$
;

CREATE OR REPLACE FUNCTION user_search_update()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.username), 'A') ||
         setweight(to_tsvector('english', NEW.firstName), 'B') ||
         setweight(to_tsvector('english', NEW.lastName), 'B') ||
         setweight(to_tsvector('english', NEW.email), 'C') ||
         setweight(to_tsvector('english', NEW.contact), 'C') 
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.username <> OLD.username OR NEW.firstName <> OLD.firstName OR NEW.lastName <> OLD.lastName OR NEW.description <> OLD.description OR NEW.email <> OLD.email) THEN
           NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.username), 'A') ||
         setweight(to_tsvector('english', NEW.firstName), 'B') ||
         setweight(to_tsvector('english', NEW.lastName), 'B') ||
         setweight(to_tsvector('english', NEW.email), 'C') ||
         setweight(to_tsvector('english', NEW.contact), 'C') 
           );
         END IF;
 END IF;
 RETURN NEW;
END $function$
;

-- ----------------------------------------------------------
-- CREATE TRIGGERS
-- ----------------------------------------------------------


create trigger user_search_update before
insert or update on
    authenticated_users
 for each row execute procedure user_search_update();


create trigger auctions_search_update before
insert or update on
    auctions for each row execute procedure auctions_search_update();
    
create trigger update_owner_balance before
update on auctions for each row execute procedure update_owner_balance();

create trigger cancel_auctions before
delete on
    auctions for each row execute procedure delete_auctions();

create trigger min_bids_diff before
insert or update on
    bids for each row execute procedure min_bids_diff();
    
create trigger owner_auctions before
insert or update on bids for each row execute procedure owner_auctions();

create trigger update_current_price after
insert or update on
    bids for each row execute procedure update_current_price();
    
create trigger check_balance before
insert or update on
    bids for each row execute procedure must_have_balance();
    
create trigger update_balance after
insert or update on
    bids for each row execute procedure update_balance();
    
create trigger already_won before
insert or update on
    bids for each row execute procedure bids_already_won();
    
create trigger update_end_date after
insert on
    bids for each row execute procedure update_end_date();
    
create trigger same_bidder before
insert or update on
    bids for each row execute procedure same_bidder();

create trigger one_reports before
insert on
    reports for each row execute procedure one_reports();
    
   
end;


insert into authenticated_users  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (1, 'Rui', 'Pires', 'rui-exe', '$2a$12$LFXiwEpPbt3t8F9aq4x/veWrfvdEFWzx.Qx/ZT7qN9rZBwXvk5YOK','rui@gmail.com','' ,'Tenho 100 instrumentos na minha garagem', '963413227', 21342.0);
insert into authenticated_users  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (2, 'Eugenie', 'Saint', 'esaint4', '$2a$12$LFXiwEpPbt3t8F9aq4x/veWrfvdEFWzx.Qx/ZT7qN9rZBwXvk5YOK','esaint4@gmail.com','' ,'Life is a wild ride', '921412112', 423423.0);
insert into authenticated_users  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (3, 'Pedro', 'Gomes', 'pedro-exe', '$2a$12$LFXiwEpPbt3t8F9aq4x/veWrfvdEFWzx.Qx/ZT7qN9rZBwXvk5YOK','pedro@gmail.com','' ,'Life is a wild ride', '921412112', 423423.0);



--reports_states 
insert into reports_states (id, state) values (1, 'open');
insert into reports_states (id, state) values (2, 'closed');

--reports
insert into reports (id, reportstext, reportsdate, reported_id, reporter_id, reports_state_id) values (1, 'O instrumento veio com defeitos', '2022-10-26 12:00:00.083', 2, 1, 1);
insert into reports (id, reportstext, reportsdate, reported_id, reporter_id, reports_state_id) values (2, 'As caravelhas do violino vieram partidas', '2022-3-16 19:14:12.086', 3, 1, 1);
insert into reports (id, reportstext, reportsdate, reported_id, reporter_id, reports_state_id) values (3, 'Está em mau estado mano', '2022-2-25 14:12:45.982', 3, 2, 1);

--categories
insert into categories (id, name) values (1, 'Percussão');
insert into categories (id, name) values (2, 'Sopro');
insert into categories (id, name) values (3, 'Cordas');
insert into categories (id, name) values (4, 'Teclas');

--manufactors
insert into manufactors (id, name) values (1, 'Yamaha');
insert into manufactors (id, name) values (2, 'Fender');
insert into manufactors (id, name) values (3, 'Gibson');
insert into manufactors (id, name) values (4, 'Ibanez');
insert into manufactors (id, name) values (5, 'Roland');
insert into manufactors (id, name) values (6, 'Casio');
insert into manufactors (id, name) values (7, 'Korg');

--auctions
insert into auctions (id, name, startprice, currentprice, startdate, lastbidsdate, enddate, minbidsdif, description, photo, owner_id, category_id, manufactor_id,winner_id) values (1 , 'violino' , 9.83, 10.97 , '2022-11-17 12:00:00.321' , NULL , '2022-11-18 11:51:59.512' , 1.18, 'Saxafone Yamaha' , '',1,2,1,NULL);
insert into auctions (id, name, startprice, currentprice, startdate, lastbidsdate, enddate,minbidsdif, description, photo, owner_id, category_id, manufactor_id,winner_id) values (2 , 'saxofone' , 4.53, 5.54 , '2023-1-5 12:40:00.567' , NULL, '2023-1-22 10:59:20.753', 8.23, 'Korg Piano' , '', 1, 4, 7, NULL);
insert into auctions (id, name, startprice, currentprice, startdate, lastbidsdate, enddate,minbidsdif, description, photo, owner_id, category_id, manufactor_id, winner_id) values (3 , 'trompa' , 5.19, 6.64 , '2023-1-1 12:23:00.000', NULL, '2023-1-20 12:40:16.245', 3.09, 'Roland Piano' , '', 2, 2, 5, NULL);

--messages :)
insert into messages (id, authenticated_user_id, auction_id, text, date) values (1, 1, 1, 'Olá tudo bem?', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (2, 1, 1, 'Em que estado o artigo se encontra?', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (3, 2, 1, 'Qual a cor do interior do violino?', '2022-11-4 12:00:00.00');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (4, 1, 1, 'Quantos anos tem de uso?', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (5, 1, 1, 'Será necessário algum tipo de arranjo?', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (6, 2, 2, 'O saxofone é tenor ou baixo?', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (8, 1, 1, 'Adorei esse violino lol', '2022-11-17 12:00:00.321');
insert into messages (id, authenticated_user_id, auction_id, text, date) values (9, 1, 1, 'Que informções deseja?','2022-11-18 12:00:00.321');

--bids :(
insert into bids (id, auction_id, authenticated_user_id, value, bidsdate) values (1, 1, 3, 20, '2022-11-17 12:00:00.321');
insert into bids (id, auction_id, authenticated_user_id, value, bidsdate) values (2, 1, 2, 25, '2022-11-18 11:31:59.512');

--follows
insert into follows (authenticated_user_id, auction_id) values (2, 1);
insert into follows (authenticated_user_id, auction_id) values (2, 2);
insert into follows (authenticated_user_id, auction_id) values (2, 3);


--reviews
insert into reviews (id, reviewer_id, reviewed_id, reviewsdate, comment, rating) values (1, 1, 3, '2022-11-7 14:12:15.544', 'Muito bom', 5);
insert into reviews (id, reviewer_id, reviewed_id, reviewsdate, comment, rating) values (2, 2, 2, '2022-12-5 15:13:43.432', 'Muito bom', 5);
insert into reviews (id, reviewer_id, reviewed_id, reviewsdate, comment, rating) values (3, 3, 1, '2022-10-4 16:43:23.561', 'Muito bom', 5);


--notifications
insert into notifications (id, date, beenread) values (1, '2022-11-17 12:12:32.123', false);
insert into notifications (id, date, beenread) values (2, '2022-7-16 12:54:32.145', false);
insert into notifications (id, date, beenread) values (3, '2022-10-5 14:12:43.143', false);
insert into notifications (id, date, beenread) values (4, '2022-7-16 12:54:32.145', false);

--admin
insert into admins (id, firstname, lastname, username,password, email,photo, description, contact)  values (1, 'Lorrie', 'Bartosch', 'lbartosch0', '$2a$12$LFXiwEpPbt3t8F9aq4x/veWrfvdEFWzx.Qx/ZT7qN9rZBwXvk5YOK','lbartosh@123.com', '' ,'Life is a wild ride', '921412112');

--auctions_cancelled_notifications
insert into auctions_cancelled_notifications (notification_id, auction_id) values (1, 1);

--auctions_ended_notifications
insert into auctions_ended_notifications (notification_id, auction_id) values (2, 2);

--auctions_ending_notifications
insert into auctions_ending_notifications (notification_id, auction_id) values (3, 3);

--bids_notifications
insert into bids_notifications (notification_id, bid_id) values (4, 1);


insert into authenticated_user_notification(authenticated_user_id,notification_id) values (2,1);
insert into authenticated_user_notification(authenticated_user_id,notification_id) values (2,2);
insert into authenticated_user_notification(authenticated_user_id,notification_id) values (2,3);
insert into authenticated_user_notification(authenticated_user_id,notification_id) values (2,4);

