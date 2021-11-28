drop database if exists cyberxgames;
create database cyberxgames default character set utf8mb4;
use cyberxgames;

create table equipment(
id int not null primary key auto_increment,
name varchar(50) not null,
price decimal(18,2) not null,
smalldesc varchar(250),
description text,
quantity int not null,
image varchar(50)
);

create table user (
id int not null primary key auto_increment,
email varchar(50) not null,
password char(60) not null,
name varchar(50) not null,
surname varchar(50) not null,
role varchar(10) not null
);

create table service (
id int not null primary key auto_increment,
name varchar(50),
smalldesc varchar(250) not null,
description text
);

create table order_details (
id int not null primary key auto_increment,
buyer int,
order_date datetime,
address varchar(50),
city varchar(30),
country varchar(30)
);

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

create table order_data (
id int not null primary key auto_increment,
order_details int not null,
equipment int,
game int,
service int,
quantity int not null
);

create table blog (
id int not null primary key auto_increment,
title varchar(50),
blogdate date,
blogsmalltext text,
bloglargetext text,
author int
);

alter table order_data add foreign key (equipment) references equipment(id);
alter table order_data add foreign key (game) references game(id);
alter table order_data add foreign key (service) references service(id);
alter table order_data add foreign key (order_details) references order_details(id);
alter table order_details add foreign key (buyer) references user(id);
alter table blog add foreign key (author) references user(id);

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
 for Microsoft Windows.');
 
# SERVICE INSERT 
insert into service (name,description,smalldesc) values 
('Computer Assembly','CyberX offers you fast and secure assembly of your computer. All of our workers are some of the most 
experienced people when it comes to computers. There is no reason to worry. The computer is returned to you assembled the same day. 
In the event of a computer connection failure, the amount paid will be refunded.','We assemble your computer in the safest and most confidential way in the fastest possible time.'),

('Console Repairs','Our console repair service has been 100% successful so far. There are no problems that our workers cannot solve. The service is very fast and efficient. 
Even the most difficult problems were solved. So if you have ANY problem with your console, feel free to contact us to resolve it!','Is something wrong with your computer? 
Or with your play station? Contact us because the problem you have can be solved very quickly.'),

('Software Installation','When we say "Software Installation", we refer to the particular configuration of a software or hardware with a view to making it usable with the computer.
 A soft or digital copy of the piece of software (program) is needed to install it. Installation may be part of a larger software deployment process. So don’t try something if you don’t
 know or aren’t sure, let our experts do it for you!','If you don’t know, you’re not sure how, or you don’t want to do something wrong while installing new softwares. Contact us.');