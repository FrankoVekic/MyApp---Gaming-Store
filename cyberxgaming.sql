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
description text,
price decimal(18,2)
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

insert into user (email,password,name,surname,role) values 
('admin@gmail.com','$2y$10$WHV1bOXJTbMzrtZEIWO97.2ycbapSP0JweaAC1iP5luFC9wosSsk2','Admin','Test','admin'),
('oper@gmail.com','$2y$10$WHV1bOXJTbMzrtZEIWO97.2ycbapSP0JweaAC1iP5luFC9wosSsk2','Operater','Test','oper');

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

('Razer Black Widow',99.99,'The Razer BlackWidow lets you experience full gaming immersion with Razer Chroma™.','The Razer BlackWidow lets you experience full gaming immersion with Razer Chroma™. 
It showcases up to 16.8 million colors in varying effects such as Spectrum Cycling, Wave, Breathing, and Ripple, and reacts to in-game events when playing Razer Chroma™ integrated games.',10,'razerblackwidow.jpg')

insert into game (name,price,quantity,memory_required,console,image,description) values 
('Pokemon',39.99,10,40,'Both','pokemon.jpg','Gotta catch them all!'),
('Spider-man',59.99,10,65,'PC','spiderman.jpg','Save the city with your favorite superhero!'),
('Biomutant',39.99,10,50,'PC','biomutant.jpg','Biomutant is an action role-playing game developed 
by Swedish developer Experiment 101 and published by THQ Nordic. The game was released on 25 May 2021
 for Microsoft Windows.');