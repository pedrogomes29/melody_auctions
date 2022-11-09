-- ----------------------------------------------------------
-- CREATE TABLES
-- ----------------------------------------------------------

Drop table if exists "admin" cascade;

CREATE TABLE "admin" (
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


Drop TABLE if exists authenticated_user cascade;

CREATE TABLE authenticated_user (
	id serial4 NOT NULL,
	firstname varchar(20) NULL,
	lastname varchar(20) NULL,
	username varchar(25) NULL,
	"password" varchar(100) NULL,
	email varchar(30) NULL,
	photo varchar(100) NULL,
	description varchar(200) NULL,
	contact varchar(15) NULL,
	balance int NULL,
	tsvectors tsvector NULL,
	CONSTRAINT authenticateduser_balance_check CHECK ((balance >= 0)),
	CONSTRAINT authenticateduser_pkey PRIMARY KEY (id)
);
CREATE INDEX user_search_idx ON authenticated_user USING gist (tsvectors);

Drop TABLE if exists category cascade;

CREATE TABLE category (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	CONSTRAINT category_name_key UNIQUE (name),
	CONSTRAINT category_pkey PRIMARY KEY (id)
);


Drop TABLE if exists manufactor cascade;

CREATE TABLE manufactor (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	CONSTRAINT manufactor_name_key UNIQUE (name),
	CONSTRAINT manufactor_pkey PRIMARY KEY (id)
);



Drop TABLE if exists notification cascade;

CREATE TABLE notification (
	id serial4 NOT NULL,
	"date" date NOT NULL,
	beenread bool NULL DEFAULT false,
	CONSTRAINT notification_pkey PRIMARY KEY (id)
);


Drop TABLE if exists report_state cascade;

CREATE TABLE report_state (
	id serial4 NOT NULL,
	state varchar(50) NOT NULL,
	CONSTRAINT reportstate_pkey PRIMARY KEY (id),
	CONSTRAINT reportstate_state_key UNIQUE (state)
);


Drop TABLE if exists auction cascade;

CREATE TABLE auction (
	id serial4 NOT NULL,
	"name" varchar(50) NOT NULL,
	startdate timestamp NOT NULL,
	startprice float8 NOT NULL,
	currentprice float8 NULL,
	lastbiddate timestamp NULL,
	minbiddif float8 NULL,
	photo varchar(100) NULL,
	description text NULL,
	ownerid int4 NOT NULL,
	categoryid int4 NOT NULL,
	manufactorid int4 NOT NULL,
	enddate timestamp NOT NULL,
	winnerid int4 NULL,
	tsvectors tsvector NULL,
	CONSTRAINT auction_check CHECK ((currentprice >= startprice)),
	CONSTRAINT auction_check1 CHECK ((lastbiddate >= startdate)),
	CONSTRAINT auction_check2 CHECK ((enddate >= startdate)),
	CONSTRAINT auction_minbiddif_check CHECK ((minbiddif >= (0)::double precision)),
	CONSTRAINT auction_pkey PRIMARY KEY (id),
	CONSTRAINT auction_startprice_check CHECK ((startprice >= (0)::double precision)),
	CONSTRAINT auction_categoryid_fkey FOREIGN KEY (categoryid) REFERENCES category(id),
	CONSTRAINT auction_manufactorid_fkey FOREIGN KEY (manufactorid) REFERENCES manufactor(id),
	CONSTRAINT auction_ownerid_fkey FOREIGN KEY (ownerid) REFERENCES authenticated_user(id),
	CONSTRAINT auction_winnerid_fkey FOREIGN KEY (winnerid) REFERENCES authenticated_user(id)
);
CREATE INDEX auction_price ON auction USING btree (currentprice);
CREATE INDEX auction_search_idx ON auction USING gist (tsvectors);


Drop TABLE if exists auction_canceled_notification cascade;

CREATE TABLE auction_canceled_notification (
	notificationid int4 NOT NULL,
	auctionid int4 NOT NULL,
	CONSTRAINT auctioncancelednotification_pkey PRIMARY KEY (notificationid),
	CONSTRAINT auctioncancelednotification_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT auctioncancelednotification_notificationid_fkey FOREIGN KEY (notificationid) REFERENCES authenticated_user(id)
);

Drop TABLE if exists auction_ended_notification cascade;

CREATE TABLE auction_ended_notification (
	notificationid int4 NOT NULL,
	auctionid int4 NOT NULL,
	CONSTRAINT auctionendednotification_pkey PRIMARY KEY (notificationid),
	CONSTRAINT auctionendednotification_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT auctionendednotification_notificationid_fkey FOREIGN KEY (notificationid) REFERENCES authenticated_user(id)
);


Drop TABLE if exists auction_ending_notification cascade;

CREATE TABLE auction_ending_notification (
	notificationid int4 NOT NULL,
	auctionid int4 NOT NULL,
	CONSTRAINT auctionendingnotification_pkey PRIMARY KEY (notificationid),
	CONSTRAINT auctionendingnotification_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT auctionendingnotification_notificationid_fkey FOREIGN KEY (notificationid) REFERENCES authenticated_user(id)
);


Drop TABLE if exists bid cascade;

CREATE TABLE bid (
	id serial4 NOT NULL,
	userid int4 NOT NULL,
	auctionid int4 NOT NULL,
	value float8 NOT NULL,
	biddate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT bid_pkey PRIMARY KEY (id),
	CONSTRAINT bid_value_check CHECK ((value >= (0)::double precision)),
	CONSTRAINT bid_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT bid_userid_fkey FOREIGN KEY (userid) REFERENCES authenticated_user(id)
);


Drop TABLE if exists bid_notification cascade;

CREATE TABLE bid_notification (
	notificationid int4 NOT NULL,
	bidid int4 NOT NULL,
	CONSTRAINT bidnotification_pkey PRIMARY KEY (notificationid),
	CONSTRAINT bidnotification_bidid_fkey FOREIGN KEY (bidid) REFERENCES bid(id),
	CONSTRAINT bidnotification_notificationid_fkey FOREIGN KEY (notificationid) REFERENCES notification(id)
);


Drop TABLE if exists follow cascade;

CREATE TABLE follow (
	userid int4 NOT NULL,
	auctionid int4 NOT NULL,
	CONSTRAINT follow_pkey PRIMARY KEY (userid, auctionid),
	CONSTRAINT follow_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT follow_userid_fkey FOREIGN KEY (userid) REFERENCES authenticated_user(id)
);

Drop TABLE if exists message cascade;

CREATE TABLE message (
	id serial4 NOT NULL,
	authenticateduserid int4 NOT NULL,
	auctionid int4 NOT NULL,
	"text" text NULL,
	"date" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT message_pkey PRIMARY KEY (id),
	CONSTRAINT message_auctionid_fkey FOREIGN KEY (auctionid) REFERENCES auction(id),
	CONSTRAINT message_authenticateduserid_fkey FOREIGN KEY (authenticateduserid) REFERENCES authenticated_user(id)
);


Drop TABLE if exists report cascade;

CREATE TABLE report (
	id serial4 NOT NULL,
	reporttext varchar(500) NOT NULL,
	reportdate date NOT NULL,
	reportstateid int4 NOT NULL,
	reportedid int4 NOT NULL,
	reporterid int4 NOT NULL,
	CONSTRAINT report_pkey PRIMARY KEY (id),
	CONSTRAINT report_reportedid_fkey FOREIGN KEY (reportedid) REFERENCES authenticated_user(id),
	CONSTRAINT report_reporterid_fkey FOREIGN KEY (reporterid) REFERENCES authenticated_user(id),
	CONSTRAINT report_reportstateid_fkey FOREIGN KEY (reportstateid) REFERENCES report_state(id)
);


Drop TABLE if exists review cascade;

CREATE TABLE review (
	id serial4 NOT NULL,
	reviewerid int4 NOT NULL,
	reviewedid int4 NOT NULL,
	reviewdate date NOT NULL DEFAULT CURRENT_DATE,
	"comment" varchar(250) NULL,
	rating int4 NOT NULL,
	CONSTRAINT review_pkey PRIMARY KEY (id),
	CONSTRAINT review_rating_check CHECK (((0 < rating) AND (rating <= 5))),
	CONSTRAINT review_reviewedid_fkey FOREIGN KEY (reviewedid) REFERENCES authenticated_user(id),
	CONSTRAINT review_reviewerid_fkey FOREIGN KEY (reviewerid) REFERENCES authenticated_user(id)
);


-- ----------------------------------------------------------
-- CREATE FUNCTIONS
-- ----------------------------------------------------------


CREATE OR REPLACE FUNCTION auction_search_update()
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

CREATE OR REPLACE FUNCTION bid_already_won()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		if EXISTS (select * from auction where new.auctionid = id and winnerid is not null) then 
		raise exception 'Cannot bid on an auction already won';
		end if;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION delete_auction()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
	if exists(select * from bid where auctionid = old.id) then 
			RAISE EXCEPTION 'Cannot erase an auction that people have bidded on';
		end if;
		return old;
	END;
$function$
;

CREATE OR REPLACE FUNCTION min_bid_diff()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE mindiff float8;
		last_price float8;
		startprice float8;

begin
		
		select auction.startprice, auction.currentprice, auction.minbiddif  from auction where NEW.auctionID = auction.id into startprice, last_price, mindiff;
		
			
		if  startprice > NEW.value THEN
           RAISE EXCEPTION 'The bid value must be greater than %.', startprice;
        elsif last_price is not null and  last_price + mindiff > NEW.value THEN
           RAISE EXCEPTION 'The bid value must be greater than % ', last_price+mindiff;
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
		if EXISTS (select * from authenticated_user where new.userid = id and new.value > balance) then 
		raise exception 'Insuficient balance to bid that value';
		end if;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION one_report()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
	IF EXISTS (SELECT * FROM Report as R WHERE NEW.reporterid = R.reporterid and R.reportedid = new.reportedid) THEN
           RAISE EXCEPTION 'Cant report a user more than once';
        END IF;
        RETURN NEW;
	END;
$function$
;

CREATE OR REPLACE FUNCTION owner_auction()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
        IF EXISTS (SELECT * FROM auction as A WHERE NEW.userid = a.ownerid and a.id = new.auctionid) THEN
           RAISE EXCEPTION 'The owner of an auction cannot bid in its own auction';
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
		if (select userid  from bid where new.auctionid = auctionid order by value desc limit 1) = new.userid then 
		raise exception 'Cannot bid on an auction that you are the last person to bid';
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
		
		select userid , value  from bid  where NEW.auctionID = bid.auctionID and new.id != bid.id order by value desc
				into last_bidder, last_price;
			
		if last_bidder is not null then
			update authenticated_user set balance = (balance+last_price) where id=last_bidder;
			
		end if;
		update authenticated_user set balance = (balance-new.value) where id=new.userid;
        RETURN NEW;
END
$function$
;

CREATE OR REPLACE FUNCTION update_current_price()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
	BEGIN
		update auction set currentprice = new.value, lastbiddate =new.biddate where auction.id = new.auctionid;
		return new;
	END;
$function$
;

CREATE OR REPLACE FUNCTION update_end_date()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
	UPDATE auction
	SET enddate = CURRENT_TIMESTAMP + (30 * interval '1 minute')
	WHERE auction.id=NEW.auctionid AND enddate-current_timestamp < (15 * interval '1 minute') ;
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
	
		if (NEW.winnerid IS NULL OR NEW.winnerid=OLD.winnerid) then
			return new;
		end if;
		
		select winnerid, currentprice from auction  where auction.id = new.id into winner, winvalue;
		
		if winvalue is null then
			raise exception 'Current Price is null';
		elsif winner is not null then
			raise exception 'Auction already has a winner';
		else
			select bid.userid  from bid where bid.auctionid = new.id and bid.value = winvalue into last_bidder;
			if last_bidder is not null then
				update authenticated_user  set balance  = (balance+ winvalue)
						where id=new.ownerid;
			else
				raise exception 'Auction already has a winner';
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
    authenticated_user for each row execute procedure user_search_update();


create trigger auction_search_update before
insert or update on
    auction for each row execute procedure auction_search_update();
    
create trigger update_owner_balance before
update on auction for each row execute procedure update_owner_balance();

create trigger cancel_auction before
delete on
    auction for each row execute procedure delete_auction();

create trigger min_bid_diff before
insert or update on
    bid for each row execute procedure min_bid_diff();
    
create trigger owner_auction before
insert or update on bid for each row execute procedure owner_auction();

create trigger update_current_price after
insert or update on
    bid for each row execute procedure update_current_price();
    
create trigger check_balance before
insert or update on
    bid for each row execute procedure must_have_balance();
    
create trigger update_balance after
insert or update on
    bid for each row execute procedure update_balance();
    
create trigger already_won before
insert or update on
    bid for each row execute procedure bid_already_won();
    
create trigger update_end_date after
insert on
    bid for each row execute procedure update_end_date();
    
create trigger same_bidder before
insert or update on
    bid for each row execute procedure same_bidder();

create trigger one_report before
insert or update on
    report for each row execute procedure one_report();
    
   
end;