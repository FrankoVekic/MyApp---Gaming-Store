drop database if exists cyberxgames;
create database cyberxgames default character set utf8mb4;
use cyberxgames;

//za online = alter database aurelije_cyberx default character set utf8mb4;



create table product(
id int not null primary key auto_increment,
name varchar(50) not null,
price decimal(18,2) not null,
description varchar(300),
quantity int not null,
console varchar(50) not null,
image varchar(50)
);

create table users (
id int not null primary key auto_increment,
email varchar(50) not null,
password char(60) not null,
name varchar(50) not null,
surname varchar(50) not null,
role varchar(10) not null
);

create table orders (
id int not null primary key auto_increment,
buyer int,
order_date datetime,
address varchar(50),
city varchar(30),
country varchar(30)
);

create table preorder(
id int not null primary key auto_increment,
name varchar(50) not null,
price decimal(18,2) not null,
description varchar(300),
quantity int not null,
memory_required int not null,
console varchar(50) not null,
image varchar(50)
);

create table game_order (
id int not null primary key auto_increment,
orders int not null,
games int not null,
quantity int
);

create table blog (
id int not null primary key auto_increment,
title varchar(50),
blogdate date,
blogtext text,
author int
);

alter table game_order add foreign key (games) references games(id);
alter table game_order add foreign key (orders) references orders(id);
alter table orders add foreign key (buyer) references users(id);
alter table blog add foreign key (author) references users(id);