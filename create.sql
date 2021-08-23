drop database if exists schedule;
create database schedule default character set utf8;

use schedule;

drop table if exists user;
create table user(
    user_id int not null primary key auto_increment,
    mail varchar(255) not null unique,
    password varchar(255) not null default '',
    name varchar(255) not null default '',
    department_id int not null,
    modified_at datetime not null default current_timestamp,
    created_at datetime not null default current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table if exists department;
create table department(
    department_id int not null primary key auto_increment,
    depart_name varchar(255) not null default ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table if exists event;
create table event(
    event_id int not null primary key auto_increment,
    user_id int not null,
    event_date date not null,
    event_allday int,
    event_start_time time not null,
    event_end_time time not null,
    event_name varchar(255) not null,
    memo text not null,
    registered_user_id int not null,
    is_delete int not null default '0',
    modified_at datetime not null default current_timestamp,
    created_at datetime not null default current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;