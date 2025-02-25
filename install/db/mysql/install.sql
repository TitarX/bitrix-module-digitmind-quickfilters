create table if not exists digitmind_multioptions_option
(
    `ID` int not null auto_increment,
    `CODE` varchar(255) not null,
    `VALUE` text not null,
    `CREATE_DATE` datetime not null,
    `UPDATE_DATE` datetime not null,
    primary key (ID)
);
