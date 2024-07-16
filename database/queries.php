alter table cadastre rename column id_user to id_utilisateur;
alter table permis rename column id_user to id_utilisateur;
alter table user_lp rename column id_entity to id_entite;
alter table certificats rename column id_user to id_utilisateur;
alter table pudi add column vu boolean default false;
