-- we don't know how to generate root <with-no-name> (class Root) :(
create table user
(
	id int auto_increment
		primary key,
	login varchar(50) not null,
    email varchar(255) not null,
	password_hash varchar(255) not null,
	created_at timestamp not null default CURRENT_TIMESTAMP(),
	constraint user_login_uindex
		unique (login),
	constraint user_email_uindex
		unique (email)
)
comment 'User table';

create table phone
(
	id int auto_increment
		primary key,
	phone_number int not null,
	email varchar(255) null,
	photo varchar(255) null,
	first_name varchar(50) not null,
	second_name varchar(50) not null,
	created_at timestamp not null default CURRENT_TIMESTAMP(),
	user_id int not null,
	constraint phone_email_uindex
		unique (email),
	constraint phone_phone_number_uindex
		unique (phone_number),
	constraint phone_user_id_fk
		foreign key (user_id) references user (id)
			on delete cascade
)
comment 'Phones book';

