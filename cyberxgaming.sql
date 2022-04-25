drop database if exists cyberxgames;
create database cyberxgames default character set utf8mb4;
use cyberxgames;

# EQUIPMENT TABLE
create table equipment(
id int not null primary key auto_increment,
name varchar(50) not null,
price decimal(18,2) not null,
smalldesc varchar(250),
description text,
quantity int not null,
image varchar(50)
);

# USER TABLE
create table user (
id int not null primary key auto_increment,
email varchar(50) not null,
password char(60) not null,
name varchar(50) not null,
surname varchar(50) not null,
role varchar(10) not null,
profile_picture varchar(50)
);

# SERVICE TABLE
create table service (
id int not null primary key auto_increment,
title varchar(50) not null,
smalldesc varchar(250) not null,
description text not null,
image varchar(50)
);

# ORDER DETAILS TABLE
create table order_details (
id int not null primary key auto_increment,
buyer int,
order_date datetime,
address varchar(50),
city varchar(30),
country varchar(30)
);


# GAME TABLE
create table game (
id int not null primary key auto_increment,
name varchar(50) not null,
price decimal(18,2) not null,
smalldesc varchar(250) not null,
description text,
quantity int not null,
memory_required int not null,
console varchar(50) not null,
image varchar(50)
);

# ORDER DATA TABLE
create table order_data (
id int not null primary key auto_increment,
order_details int not null,
equipment int,
game int,
quantity int not null
);

# BLOG TABLE
create table blog (
id int not null primary key auto_increment,
title varchar(50) not null,
blogdate date not null default now(),
`text` text not null,
author int not null,
image varchar(50)
);

# NEWS TABLE 
create table news(
id int not null primary key auto_increment,
headline varchar(250) not null,
`text` text not null,
publishDate date default now(),
image varchar(50) not null,
author int not null
);

# COMENT TABLE
create table comment(
id int not null primary key auto_increment,
writer int not null,
comment text not null,
commentDate datetime not null default now(),
post int not null
);


# ALTERS
alter table order_data add foreign key (equipment) references equipment(id);
alter table order_data add foreign key (game) references game(id);
alter table order_data add foreign key (order_details) references order_details(id);
alter table order_details add foreign key (buyer) references user(id);
alter table blog add foreign key (author) references user(id);
alter table news add foreign key (author) references user(id);
alter table comment add foreign key (writer) references user(id);
alter table comment add foreign key (post) references blog(id) ON DELETE CASCADE;

# USER INSERT
insert into user (email,password,name,surname,role) values 
('admin@gmail.com','$2y$10$WHV1bOXJTbMzrtZEIWO97.2ycbapSP0JweaAC1iP5luFC9wosSsk2','Admin','Test','admin'),
('oper@gmail.com','$2y$10$WHV1bOXJTbMzrtZEIWO97.2ycbapSP0JweaAC1iP5luFC9wosSsk2','Operater','Test','oper');


# EQUIPMENT INSERT
insert into equipment (name,price,smalldesc,description,quantity,image) values 
('Razer Lachesis',79.99,'The Razer Lachesis reigns supreme with a true 5600dpi 3.5G Laser sensor.','The Razer Lachesis reigns supreme with a true 5600dpi 3.5G Laser sensor, which 
enables movement speeds of 7 times that of a standard 800dpi optical sensor. Customize the look of the Razer 
Lachesis however you want. With the new Tricolor-LED you get a unique look & style on your favourite gaming weapon.',10,'razerlachesis.jpg'),

('Razer Nari',80,'The wireless gaming headset designed for supreme comfort.','The wireless gaming headset designed for supreme comfort. Featuring an auto-adjusting headband, with swiveling 
earcups that include cooling gel-infused ear cushions. THX Spatial Audio provides next-generation surround sound and the game/chat 
balance allows the perfect mix of game and chat volume.',10,'razernari.jpg'),

('Red CyberX chair',100,'A cyberx gaming chair is a type of chair designed for the comfort of gamers.','A cyberx gaming chair is a type of chair designed for the comfort of gamers. They differ from most office chairs
 in having high backrest designed to support the upper back and shoulders. They are also more customizable: the armrests, back, lumbar support 
and headrest can all be adjusted for comfort and efficiency.',10,'redcyberxchair.jpg'),

('Razer Black Widow',99.99,'The Razer BlackWidow lets you experience full gaming immersion with Razer Chroma.','The Razer BlackWidow lets you experience full gaming immersion with Razer Chroma. 
It showcases up to 16.8 million colors in varying effects such as Spectrum Cycling, Wave, Breathing, and Ripple, and reacts to in-game events when playing Razer Chroma integrated games.',10,'razerblackwidow.jpg');

# GAME INSERT
insert into game (name,price,smalldesc,quantity,memory_required,console,image,description) values 
('Pokemon',39.99,'Pokémon is a role-playing game based around building a small team of monsters to battle other monsters in a quest to become the best.',10,40,'Both','pokemon.jpg','Gotta catch them all!'),

('Spider-man',59.99,'Spider-Man is an open-world third-person action-adventure game, in which the player controls Peter Parker.',10,65,'PC','spiderman.jpg','Save the city with your favorite superhero!'),

('Biomutant',39.99,'BIOMUTANT is an open-world, post-apocalyptic Kung-Fu fable RPG, with a unique martial arts styled combat system.',10,50,'PC','biomutant.jpg','Biomutant is an action role-playing game developed 
by Swedish developer Experiment 101 and published by THQ Nordic. The game was released on 25 May 2021
 for Microsoft Windows.'),('Pokemon',39.99,'Pokémon is a role-playing game based around building a small team of monsters to battle other monsters in a quest to become the best.',10,40,'Both','pokemon.jpg','Gotta catch them all!'),

('Spider-man',59.99,'Spider-Man is an open-world third-person action-adventure game, in which the player controls Peter Parker.',10,65,'PC','spiderman.jpg','Save the city with your favorite superhero!'),

('Biomutant',39.99,'BIOMUTANT is an open-world, post-apocalyptic Kung-Fu fable RPG, with a unique martial arts styled combat system.',10,50,'PC','biomutant.jpg','Biomutant is an action role-playing game developed 
by Swedish developer Experiment 101 and published by THQ Nordic. The game was released on 25 May 2021
 for Microsoft Windows.'),('Pokemon',39.99,'Pokémon is a role-playing game based around building a small team of monsters to battle other monsters in a quest to become the best.',10,40,'Both','pokemon.jpg','Gotta catch them all!'),

('Spider-man',59.99,'Spider-Man is an open-world third-person action-adventure game, in which the player controls Peter Parker.',10,65,'PC','spiderman.jpg','Save the city with your favorite superhero!'),

('Biomutant',39.99,'BIOMUTANT is an open-world, post-apocalyptic Kung-Fu fable RPG, with a unique martial arts styled combat system.',10,50,'PC','biomutant.jpg','Biomutant is an action role-playing game developed 
by Swedish developer Experiment 101 and published by THQ Nordic. The game was released on 25 May 2021
 for Microsoft Windows.');
 
# SERVICE INSERT 
insert into service (title,description,smalldesc,image) values 
('Computer Assembly','CyberX offers you fast and secure assembly of your computer. All of our workers are some of the most 
experienced people when it comes to computers. There is no reason to worry. The computer is returned to you assembled the same day. 
In the event of a computer connection failure, the amount paid will be refunded.','We assemble your computer in the safest and most confidential way in the fastest possible time.','pcmake.jpg'),

('Console Repairs','Our console repair service has been 100% successful so far. There are no problems that our workers cannot solve. The service is very fast and efficient. 
Even the most difficult problems were solved. So if you have ANY problem with your console, feel free to contact us to resolve it!','Is something wrong with your computer? 
Or with your play station? Contact us because the problem you have can be solved very quickly.','crepair.jpg'),

('Software Installation','When we say "Software Installation", we refer to the particular configuration of a software or hardware with a view to making it usable with the computer.
 A soft or digital copy of the piece of software (program) is needed to install it. Installation may be part of a larger software deployment process. So don’t try something if you don’t
 know or aren’t sure, let our experts do it for you!','If you don’t know, you’re not sure how, or you don’t want to do something wrong while installing new softwares. Contact us.','sfin.jpg'),
 ('Computer Assembly','CyberX offers you fast and secure assembly of your computer. All of our workers are some of the most 
experienced people when it comes to computers. There is no reason to worry. The computer is returned to you assembled the same day. 
In the event of a computer connection failure, the amount paid will be refunded.','We assemble your computer in the safest and most confidential way in the fastest possible time.','pcmake.jpg'),

('Console Repairs','Our console repair service has been 100% successful so far. There are no problems that our workers cannot solve. The service is very fast and efficient. 
Even the most difficult problems were solved. So if you have ANY problem with your console, feel free to contact us to resolve it!','Is something wrong with your computer? 
Or with your play station? Contact us because the problem you have can be solved very quickly.','crepair.jpg'),

('Software Installation','When we say "Software Installation", we refer to the particular configuration of a software or hardware with a view to making it usable with the computer.
 A soft or digital copy of the piece of software (program) is needed to install it. Installation may be part of a larger software deployment process. So don’t try something if you don’t
 know or aren’t sure, let our experts do it for you!','If you don’t know, you’re not sure how, or you don’t want to do something wrong while installing new softwares. Contact us.','sfin.jpg'),
 ('Computer Assembly','CyberX offers you fast and secure assembly of your computer. All of our workers are some of the most 
experienced people when it comes to computers. There is no reason to worry. The computer is returned to you assembled the same day. 
In the event of a computer connection failure, the amount paid will be refunded.','We assemble your computer in the safest and most confidential way in the fastest possible time.','pcmake.jpg'),

('Console Repairs','Our console repair service has been 100% successful so far. There are no problems that our workers cannot solve. The service is very fast and efficient. 
Even the most difficult problems were solved. So if you have ANY problem with your console, feel free to contact us to resolve it!','Is something wrong with your computer? 
Or with your play station? Contact us because the problem you have can be solved very quickly.','crepair.jpg'),

('Software Installation','When we say "Software Installation", we refer to the particular configuration of a software or hardware with a view to making it usable with the computer.
 A soft or digital copy of the piece of software (program) is needed to install it. Installation may be part of a larger software deployment process. So don’t try something if you don’t
 know or aren’t sure, let our experts do it for you!','If you don’t know, you’re not sure how, or you don’t want to do something wrong while installing new softwares. Contact us.','sfin.jpg');
 
# NEWS INSERT
insert into news (headline,text,image,author,publishDate) values ('WE ARE OPEN!','For the past year, cyberx games have only worked through online sales. But after the decision of Franko Vekić, the 
founder of the company itself, we are glad to inform you that we are opening our first store! From 6.2.2021 you will be able to come to our store in person, watch, play or buy games with your friends. 
Cyberx stores will have a gaming room where you will be able to spend time with your loved ones and play all possible games indefinitely. In addition to computers, we will have a gaming room with play stations.
 The store opens at 1 Pere Perića Street, Osijek. We expect you in as many numbers as possible with a large number of top games, computer equipment and good fun.','open.jpg',1,'2021-01-11');

insert into news (headline,text,image,author,publishDate) values ('The arrival of new quality equipment.','In the past half year, we have been doing better than in the whole of 2020. Our result could not go unnoticed. 
So we got a couple of good offers to work with one of the best companies selling computer equipment. Some of them are: Razer, Logitech and SteelSeries. Of course we have accepted the cooperation, so we are very 
glad to inform you that from now on we have a lot of new equipment waiting for you in our store. In the next few weeks we will come up with another good announcement that has to do with games. We will just tell 
you that the problem of lack of games will no longer be a problem!','pship.jpg',1,'2021-05-21');

insert into news (headline,text,image,author,publishDate) values ('New games have arrived.','As we announced to you, that day has arrived. Exactly two weeks ago we got a very attractive offer for 100 new games, 70 for PC and 
30 for play station consoles. We knew the offer was good but we knew we could get a lot more games for a little more money. So we offered that, and of course they accepted the offer. We have just received about two hundred
 new games for the PC and about a hundred for the play station console. The vast majority of these games were produced a year and a half ago. But we didn’t just want to take the new ones but we also took some old ones that 
you probably remember.','newgames.png',1,'2021-06-04');

insert into news (headline,text,image,author,publishDate) values ('New stores are opening!','First of all, I would like to thank you for the great response to purchases in our store over the past year and more. We exploded.
 There are so many of you that you made us open our stores in other cities as well. I am pleased to announce that we are opening stores in: Zagreb, Varaždin, Vukovar, Slavonski Brod, Split, Dubrovnik, Rijeka and Zadar. 
The first open store will be in Zagreb on July 22, 2021. After that, July 24, 2021 in Split. By the end of the year, we will open all the stores listed. Thank you for everything and I look forward to your visit to our
 stores.','nstore.jpg',1,'2021-07-15');
 

# BLOG INSERT
insert into blog (title,`text`,author,image) values

('How do i change the color of my gaming mouse?','How can I make my colors change constantly on razer lachesis. At the moment, red is constantly shining on me, 
and I would like it to change all the time. Can someone please help me?',2,null),

('CoD Warzone ALL BUNKERS','Hello everyone, after a couple of hours of searching, I found all the bunkers in the warzon. I am sending you a picture attached. Have fun!',1,'bunkers.jpg'),

('How do i change the color of my gaming mouse?','How can I make my colors change constantly on razer lachesis. At the moment, red is constantly shining on me, 
and I would like it to change all the time. Can someone please help me?',2,null),

('CoD Warzone ALL BUNKERS','Hello everyone, after a couple of hours of searching, I found all the bunkers in the warzon. I am sending you a picture attached. Have fun!',1,'bunkers.jpg');

# COMMENT INSERT 
insert into comment (writer,comment,post) values (2,'Thank you very much! I play alot of Warzone. This will help me alot.',2);
insert into comment (writer,comment,post) values (2,'Thank you very much! I play alot of Warzone. This will help me alot.',2);