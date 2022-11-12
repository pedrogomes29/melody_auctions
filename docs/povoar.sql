--user
insert into authenticated_user  (id, firstname, lastname, username,password, email,photo, description, contact,balance ) values (1, 'Crissy', 'Petley', 'cpetley0','qwerty' ,'cpetley0@wordpress.org',' ' ,'Música é vida', '929507690',234.0);
insert into authenticated_user  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (2, 'Reece', 'Bainton', 'rbainton1','1234321' ,'rbainton1@unc.edu', ' ','melhor clarinetista português', '919119298',23423.0);
insert into authenticated_user  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (3, 'Benedetta', 'Driutti', 'bdriutti2','lmaook1234' ,'bdriutti2@last.fm', ' ','vive a vida', '916135290', 1312.0);
insert into authenticated_user  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (4, 'Leontine', 'Matteoli', 'lmatteoli3', 'xdlollmaokekw','lmatteoli3@wordpress.org',' ' ,'Tenho 100 instrumentos na minha garagem', '963413227', 21342.0);
insert into authenticated_user  (id, firstname, lastname, username,password, email,photo, description, contact,balance) values (5, 'Eugenie', 'Saint', 'esaint4', 'gigachad493','esaint4@sphinn.com',' ' ,'Life is a wild ride', '921412112', 423423.0);

--report_state 
insert into report_state (id, state) values (1, 'open');
insert into report_state (id, state) values (2, 'closed');

--report
insert into report (id, reporttext, reportdate, reportedid, reporterid, reportstateid) values (1, 'O instrumento veio com defeitos', '2022-10-26 12:00:00.083', 2, 3, 1);
insert into report (id, reporttext, reportdate, reportedid, reporterid, reportstateid) values (2, 'As caravelhas do violino vieram partidas', '2022-3-16 19:14:12.086', 4, 1, 1);
insert into report (id, reporttext, reportdate, reportedid, reporterid, reportstateid) values (3, 'Unspecified injury of prostate', '2022-2-25 14:12:45.982', 4, 3, 1);

--category
insert into category (id, name) values (1, 'Percussão');
insert into category (id, name) values (2, 'Sopro');
insert into category (id, name) values (3, 'Cordas');
insert into category (id, name) values (4, 'Teclas');

--manufactor
insert into manufactor (id, name) values (1, 'Yamaha');
insert into manufactor (id, name) values (2, 'Fender');
insert into manufactor (id, name) values (3, 'Gibson');
insert into manufactor (id, name) values (4, 'Ibanez');
insert into manufactor (id, name) values (5, 'Roland');
insert into manufactor (id, name) values (6, 'Casio');
insert into manufactor (id, name) values (7, 'Korg');

--auction
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate, minbiddif, description, photo, ownerid, categoryid, manufactorid,winnerid) values (1, 'piano', 9.83, 10.97, '2022-11-17 12:00:00.321', '2022-11-18 11:31:59.512', '2022-11-18 11:31:59.512',1.18, 'Saxafone Yamaha',' ',2,2,1,3);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid,winnerid) values (2, 'clarinete', 5.19, 6.64, '2022-7-16 12:23:00.425', '2022-7-16 15:40:16.245', '2022-7-16 15:40:16.245',3.09, 'Fender Violin',' ',4,3,2,1);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid,winnerid) values (3, 'guitarra', 7.75, 9.20, '2022-10-4 12:42:00.543', '20022-10-3 11:04:15.654','20022-10-3 11:04:15.654' ,5.34, 'Yamaha Piano',' ',5,4,1,2);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid,winnerid) values (4, 'saxofone', 0.26, 1.14, '2022-11-17 12:50:00.512', '2022-11-17 15:05:42.764','2022-11-17 15:05:42.764' ,0.06, 'Ibanez Double Bass', ' ',2,3,4,3);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid,winnerid) values (5, 'trompete', 4.53, 5.54, '2022-4-15 12:40:00.567', '2022-4-22 10:59:20.753', '2022-4-22 10:59:20.753',8.23, 'Korg Piano',' ',1,4,7,5);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid, winnerid) values (6, 'trombone', 3.12, 4.23, '2022-3-16 12:00:00.321', '2022-3-16 15:31:59.512', '2022-3-16 15:31:59.512',1.18, 'Casio Piano',' ',2,1,6,NULL);
insert into auction (id, name, startprice, currentprice, startdate, lastbiddate, enddate,minbiddif, description, photo, ownerid, categoryid, manufactorid, winnerid) values (7, 'flauta', 5.19, 6.64, '2022-7-16 12:23:00.425', '2022-7-16 15:40:16.245', '2022-7-16 15:40:16.245',3.09, 'Roland Piano',' ',4,2,5,NULL);

--message 
insert into message (id, authenticateduserid, auctionid, text, date) values (1, 3, 4, 'Olá tudo bem?', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (2, 3, 5, 'Que informções deseja?','2022-11-18 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (3, 1, 3, 'Em que estado o artigo se encontra?', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (4, 2, 3, 'Qual a cor do interior do violino?', '2022-11-4 12:00:00.00');
insert into message (id, authenticateduserid, auctionid, text, date) values (5, 1, 1, 'Quantos anos tem de uso?', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (6, 1, 1, 'Será necessário algum tipo de arranjo?', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (7, 2, 2, 'O saxofone é tenor ou baixo?', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (8, 3, 4, 'espero que essa guitarra seja da yamaha', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (9, 2, 5, 'Adorei esse violino lol', '2022-11-17 12:00:00.321');
insert into message (id, authenticateduserid, auctionid, text, date) values (10, 1, 3, 'É mesmo giro o clarinete', '2022-11-17 12:00:00.321');

--bid
insert into bid (id, auctionid, userid, value, biddate) values (1, 6, 5, 20, '2022-11-17 12:00:00.321');
insert into bid (id, auctionid, userid, value, biddate) values (2, 7, 5, 20, '2022-7-16 12:23:00.425');

--follow
insert into follow (userid, auctionid) values (1, 1);
insert into follow (userid, auctionid) values (2, 2);
insert into follow (userid, auctionid) values (3, 3);
insert into follow (userid, auctionid) values (4, 4);
insert into follow (userid, auctionid) values (5, 5);

--review
insert into review (id, reviewerid, reviewedid, reviewdate, comment, rating) values (1, 1, 2, '2022-11-7 14:12:15.544', 'Muito bom', 5);
insert into review (id, reviewerid, reviewedid, reviewdate, comment, rating) values (2, 2, 3, '2022-12-5 15:13:43.432', 'Muito bom', 5);
insert into review (id, reviewerid, reviewedid, reviewdate, comment, rating) values (3, 3, 4, '2022-10-4 16:43:23.561', 'Muito bom', 5);


--notification
insert into notification (id, date, beenread) values (1, '2022-11-17 12:12:32.123', false);
insert into notification (id, date, beenread) values (2, '2022-7-16 12:54:32.145', false);
insert into notification (id, date, beenread) values (3, '2022-10-5 14:12:43.143', false);
insert into notification (id, date, beenread) values (4, '2022-7-16 12:54:32.145', false);

--admin
insert into admin (id, firstname, lastname, username,password, email,photo, description, contact)  values (1, 'Lorrie', 'Bartosch', 'lbartosch0', 'gigachad493','lbartosh@123.com', ' ' ,'Life is a wild ride', '921412112');

--auction_canceled_notification
insert into auction_canceled_notification (notificationid, auctionid) values (1, 1);

--auction_ended_notification
insert into auction_ended_notification (notificationid, auctionid) values (2, 2);

--auction_ending_notification
insert into auction_ending_notification (notificationid, auctionid) values (3, 3);

--bid_notification
insert into bid_notification (notificationid, bidid) values (4, 1);