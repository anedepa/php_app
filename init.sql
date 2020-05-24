create database dsp;

grant all on dsp.* to root@localhost identified by '000000';

use dsp

create table users (
  id int not null auto_increment primary key,
  email varchar(255) unique,
  password varchar(255),
  created datetime,
  modified datetime
);
