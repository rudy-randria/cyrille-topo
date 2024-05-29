alter table certificats add column vu boolean default 'false';
alter table permis add column vu boolean default 'false';
alter table certificats rename column id_utilisateur to id_user;
alter table permis rename column id_utilisateur to id_user;