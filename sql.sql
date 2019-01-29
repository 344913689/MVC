drop table if exists ask_category;
create table ask_category(
cat_id int primary key auto_increment, 
cat_name varchar(32), 
cat_logo varchar(128), 
cat_desc varchar(32), 
parent_id int)engine myisam default charset utf8;

drop table if exists ask_user;
create table ask_user(
user_id int primary key auto_increment,
username varchar(32) not null,
password varchar(64) not null,
email varchar(32),
phone char(15) not null,
member tinyint default 0,
is_action tinyint default 1,
reg_time datetime,
user_pic varchar(28))engine myisam default charset utf8;

drop table if exists ask_question;
create table ask_question(
question_id int primary key auto_increment,
question_title varchar(128) not null,
question_desc text,
cat_id tinyint not null,
topic_id int not null,
user_id int not null,
pub_time datetime,
focus_num int,
reply_num int,
view_num int)engine myisam default charset utf8;

drop table if exists ask_question_topic;
create table ask_question_topic(
qt_id int primary key auto_increment,
question_id int not null,
topic_id int not null)engine myisam default charset utf8;

drop table if exists ask_topic;
create table ask_topic(
topic_id int primary key auto_increment,
topic_title varchar(128) not null,
topic_desc text,
topic_pic varchar(128))engine myisam default charset utf8;

drop table if exists ask_member;
create table ask_member(
member_id int primary key auto_increment,
start_time datetime not null,
end_time datetime not null,
user_id int not null)engine myisam default charset utf8;
