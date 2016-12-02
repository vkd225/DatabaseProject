CREATE TABLE comments
(
  commenter names,
  commented_on names,
  body character varying(300),
  time_comment timestamp without time zone,
  CONSTRAINT comments_commented_on_fkey FOREIGN KEY (commented_on)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT comments_commenter_fkey FOREIGN KEY (commenter)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE comments_diaryentry
(
  commenter names,
  commented_on names,
  body character varying(300),
  time_comment timestamp without time zone,
  diaryentry_id integer,
  CONSTRAINT comments_diaryentry_commented_on_fkey FOREIGN KEY (commented_on)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT comments_diaryentry_commenter_fkey FOREIGN KEY (commenter)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT comments_diaryentry_diaryentry_id_fkey FOREIGN KEY (diaryentry_id)
      REFERENCES user_diary (diaryentry_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE friendship
(
  user_name names,
  friend_id names,
  status integer,
  time_request_sent timestamp without time zone,
  time_request_accepted timestamp without time zone,
  CONSTRAINT friendship_user_name_fkey FOREIGN KEY (user_name)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT friendship_status_check CHECK (status = 1 OR status = 2)
)

CREATE TABLE imagetable
(
  user_name names,
  "Photo" oid,
  CONSTRAINT imagetable_user_name_fkey FOREIGN KEY (user_name)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE last_access
(
  user_name names,
  logout timestamp without time zone,
  CONSTRAINT last_access_user_name_fkey FOREIGN KEY (user_name)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE user_diary
(
  diaryentry_id serial NOT NULL,
  user_name names NOT NULL,
  title character varying(30) NOT NULL,
  body character varying(1000),
  time_posted timestamp without time zone NOT NULL,
  privacy integer,
  CONSTRAINT user_diary_pkey PRIMARY KEY (diaryentry_id),
  CONSTRAINT user_diary_user_name_fkey FOREIGN KEY (user_name)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE user_profile
(
  user_name names NOT NULL,
  profile character varying(1000),
  time_posted timestamp without time zone,
  CONSTRAINT user_profile_pkey PRIMARY KEY (user_name),
  CONSTRAINT user_profile_user_name_fkey FOREIGN KEY (user_name)
      REFERENCES users (user_name) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)

CREATE TABLE users
(
  first_name names,
  last_name names,
  user_name names NOT NULL,
  password character varying(10),
  age numeric(2,0),
  city character varying(15),
  privacy integer,
  CONSTRAINT users_pkey PRIMARY KEY (user_name),
  CONSTRAINT users_privacy_check CHECK (privacy = 1 OR privacy = 2 OR privacy = 3)
)

CREATE OR REPLACE FUNCTION add_comment(
    _user_name names,
    _commenter names,
    _body character varying)
  RETURNS void AS
$BODY$
begin
insert into comments(commenter, commented_on, body, time_comment)
values (_commenter, _user_name, _body, NOW());
end;

CREATE OR REPLACE FUNCTION bytea_import(
    IN p_path text,
    OUT p_result bytea)
  RETURNS bytea AS
$BODY$
declare
  l_oid oid;
  r record;
begin
  p_result := '';
  select lo_import(p_path) into l_oid;
  for r in ( select data 
             from pg_largeobject 
             where loid = l_oid 
             order by pageno ) loop
    p_result = p_result || r.data;
  end loop;
  perform lo_unlink(l_oid);
end;$BODY$

CREATE OR REPLACE FUNCTION sp_accept_friend_request(
    _user_name names,
    _friend_id names,
    _status integer)
  RETURNS void AS
$BODY$

begin

update friendship set status=_status ,time_request_accepted=now()

where user_name=_user_name and friend_id=_friend_id;

end;

CREATE OR REPLACE FUNCTION sp_check_new_diary_entry_friends(viewer names)
  RETURNS SETOF names AS
$BODY$
begin 
return query
with my_friends as (
select user_name from users where user_name in (select friend_id from friendship where user_name=viewer or friend_id =viewer 
and status=1 ) or user_name in (select user_name from friendship where user_name=viewer or friend_id =viewer and status=1 ) )
select user_name from my_friends join user_diary using(user_name) where user_name!=viewer and time_posted > (select max(logout) from last_access where user_name = viewer  );
end;

CREATE OR REPLACE FUNCTION sp_check_new_friend_request(viewer names)
  RETURNS SETOF names AS
$BODY$
begin 
return query
select distinct(user_name) from users join friendship using(user_name) where user_name in (select user_name from friendship where friend_id=viewer and status =2) 
or user_name in(select friend_id from friendship where user_name=viewer and status =2) and 
time_request_sent > (select max(logout) from last_access where user_name = viewer  );

end;

CREATE OR REPLACE FUNCTION sp_check_new_profile_friends(viewer names)
  RETURNS SETOF names AS
$BODY$
begin 
return query
with my_friends as (
select user_name from users where user_name in (select friend_id from friendship where user_name=viewer or friend_id =viewer 
and status=1 ) or user_name in (select user_name from friendship where user_name=viewer or friend_id =viewer and status=1 )  )
select user_name from my_friends join user_profile using(user_name) where user_name!=viewer and time_posted > (select max(logout) from last_access where user_name = viewer  );

end;

CREATE OR REPLACE FUNCTION sp_default_profile(_user_name names)
  RETURNS void AS
$BODY$
begin
insert into user_profile(user_name, profile)
values (_user_name, 'I am using Techies.com');
end;

CREATE OR REPLACE FUNCTION sp_delete_diary_entry(_id integer)
  RETURNS void AS
$BODY$

begin

delete from user_diary 

where diaryentry_id=_id;

end;

CREATE OR REPLACE FUNCTION sp_delete_friend_request(
    _user_name names,
    _friend_id names)
  RETURNS void AS
$BODY$

begin

delete from friendship 

where user_name=_user_name and friend_id=_friend_id;

end;

CREATE OR REPLACE FUNCTION sp_edit_user_profile(
    _user_name names,
    _body character varying)
  RETURNS void AS
$BODY$
begin
update user_profile
set profile=_body,time_posted=now()
where user_name=_user_name;
end;

CREATE OR REPLACE FUNCTION sp_insert_profile(
    _user_name names,
    _profile character varying)
  RETURNS void AS
$BODY$

begin 
insert into user_profile(user_name,profile,time_posted)

values (_user_name,_profile,now() );

end;

CREATE OR REPLACE FUNCTION sp_insert_user_diary(
    _user_name names,
    _title character varying,
    _body character varying,
    _privacy integer)
  RETURNS void AS
$BODY$

begin

insert into user_diary(user_name,

title,

body ,

time_posted,

privacy

)

values (_user_name,_title,_body,NOW(),_privacy);

end;



CREATE OR REPLACE FUNCTION sp_insert_user_diary_comment(
    _commenter names,
    _commented_on character varying,
    _body character varying,
    _diaryentry_id integer)
  RETURNS void AS
$BODY$

begin

insert into comments_diaryentry(commenter,

commented_on,

body ,

time_comment,

diaryentry_id

)

values (_commenter,_commented_on,_body,NOW(),_diaryentry_id);

end;

CREATE OR REPLACE FUNCTION sp_logout_time(_user_name names)
  RETURNS void AS
$BODY$

begin

insert into last_access(user_name,logout
)

values (_user_name,NOW());

end;

CREATE OR REPLACE FUNCTION sp_post_diary_entry(IN _user_name names)
  RETURNS TABLE(diaryentry_id_ integer, title_ character varying, body_ character varying, time_comment_ timestamp without time zone, privacy_ integer) AS
$BODY$ 
begin
return query
select diaryentry_id,title,body,time_posted,privacy
from user_diary
where user_name=_user_name
order by time_posted DESC;
end;


CREATE OR REPLACE FUNCTION sp_search_comments_by_commented_on(IN _commented_on names)
  RETURNS TABLE(commenter_ names, body_ character varying, time_comment_ timestamp without time zone) AS
$BODY$ 
begin
return query
select commenter,body,time_comment
from comments
where commented_on=_commented_on
order by time_comment DESC;
end;

CREATE OR REPLACE FUNCTION sp_search_comments_by_commenter(IN _commenter names)
  RETURNS TABLE(commented_on_ names, body_ character varying, time_comment_ timestamp without time zone) AS
$BODY$ 
begin
return query
select commented_on,body,time_comment
from comments
where commenter=_commenter;
end;

CREATE OR REPLACE FUNCTION sp_search_users(keyword character varying)
  RETURNS SETOF names AS
$BODY$
begin 
return query
 select user_name from users where user_name ilike '%'||keyword||'%' or last_name ilike '%'||keyword||'%' or first_name ilike '%'||keyword||'%';
end;

CREATE OR REPLACE FUNCTION sp_send_friend_request(
    _user_name names,
    _friend_id names)
  RETURNS void AS
$BODY$

begin

insert into friendship(user_name,friend_id,status,time_request_sent)

values(_user_name,_friend_id,2,now());

end;




CREATE OR REPLACE FUNCTION sp_send_friend_request(
    _user_name names,
    _friend_id names,
    _status integer)
  RETURNS void AS
$BODY$

begin

insert into friendship(user_name,friend_id,status,time_request_sent)

values(_user_name,_friend_id,_status,now());

end;

CREATE OR REPLACE FUNCTION sp_show_friend(_user_name names)
  RETURNS SETOF names AS
$BODY$

begin
return query

 with my_friend as (
select user_name from users 
where user_name in (select user_name from friendship where friend_id=_user_name and status=1) 
or user_name in (select friend_id from friendship where  status=1 and user_name=_user_name  ))

select * from my_friend;
end;

CREATE OR REPLACE FUNCTION sp_show_friend_of_friend(_user_name names)
  RETURNS SETOF names AS
$BODY$

begin
return query

 with my_friend as (
select user_name from users 
where user_name in (select user_name from friendship where friend_id=_user_name and status=1) 
or user_name in (select friend_id from friendship where  status=1 and user_name=_user_name  ))

select user_name from users
where(( user_name in (select friend_id from friendship where user_name in(select user_name from my_friend) and status=1))
or (user_name in (select user_name from friendship where friend_id in(select user_name from my_friend) and status=1)))
and user_name not in (select user_name from my_friend)
and user_name !=_user_name;
end;

CREATE OR REPLACE FUNCTION sp_show_friend_request(IN _user_name names)
  RETURNS TABLE(friend_id_ names) AS
$BODY$

begin
return query

select user_name from friendship where friend_id=_user_name and status=2;
 

end;

CREATE OR REPLACE FUNCTION sp_show_user_diary_comment(IN _user_name names)
  RETURNS TABLE(commenter_ names, body_ character varying, time_comment_ timestamp without time zone, diaryentry_id integer) AS
$BODY$ 
begin
return query
select c.commenter,c.body,c.time_comment,c.diaryentry_id
from comments_diaryentry c join user_diary d using (diaryentry_id)
where d.user_name=_user_name;
end;

CREATE OR REPLACE FUNCTION sp_show_user_diary_comment_updated(IN _diary_entry_id integer)
  RETURNS TABLE(commenter_ names, body_ character varying, time_comment_ timestamp without time zone) AS
$BODY$ 
begin
return query
select c.commenter,c.body,c.time_comment
from comments_diaryentry c join user_diary d using (diaryentry_id)
where diaryentry_id=_diary_entry_id
order by c.time_comment DESC;
end;

CREATE OR REPLACE FUNCTION sp_signup(
    _first_name names,
    _last_name names,
    _user_name names,
    _password character varying,
    _age numeric,
    _city character varying,
    _privacy integer)
  RETURNS void AS
$BODY$

begin

insert into users(first_name,last_name,user_name,password,age,city,privacy)

values(_first_name,_last_name,_user_name,_password,_age,_city,_privacy);

end;


CREATE OR REPLACE FUNCTION sp_update_privacy(
    _user_name names,
    _privacy integer)
  RETURNS void AS
$BODY$
begin
update users set privacy=_privacy where user_name=_user_name;
end;


CREATE OR REPLACE FUNCTION sp_update_user(
    _user_name names,
    _first_name names,
    _last_name names,
    _password character varying,
    _age numeric,
    _privacy integer)
  RETURNS void AS
$BODY$
begin
update users set privacy=_privacy,first_name=_first_name,last_name=_last_name,password=_password,age=_age,privacy=_privacy where user_name=_user_name;
end;

CREATE OR REPLACE FUNCTION sp_update_user_profile(
    _user_name names,
    _profile character varying)
  RETURNS void AS
$BODY$

begin update user_profile set

profile=_profile where

user_name=_user_name

;

end;

CREATE OR REPLACE FUNCTION sp_user_profile(
    _user_name names,
    _profile character varying)
  RETURNS void AS
$BODY$

begin 
insert into user_profile(user_name,profile)

values (_user_name,_profile );

end;


CREATE OR REPLACE FUNCTION sp_view_last_week_diary_entry(IN _user_name names)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$ 
begin
return query
select user_name, title, body, time_posted
from user_diary
where user_name in ( select friend_id from friendship where user_name=_user_name) and time_posted > NOW()-'7 days'::interval;
end;

CREATE OR REPLACE FUNCTION sp_view_user_diary_entry_friend(
    IN _user_name names,
    IN viewer names,
    IN _keyword character varying)
  RETURNS TABLE(title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
with friend_type as
(select status from friendship where user_name=_user_name and friend_id=viewer )
select title, body,time_posted from user_diary join users using(user_name),friend_type where user_name =_user_name and body ilike '%'||_keyword ||'%' and users.privacy = 1 and friend_type.status=1;
end;

CREATE OR REPLACE FUNCTION sp_view_user_diary_entry_friend_of_friend(
    IN _user_name names,
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
with my_friend as 
 (select friend_id from friendship where user_name =_user_name),
friend_of_friend as (
select friend_id from friendship where user_name in 
 (select friend_id from my_friend)
 group by friend_id)
select user_name,title,body,time_posted from user_diary join users using (user_name) where user_name =_user_name and users.privacy = 2 and body ilike '%'||keyword||'%' and exists (select friend_id from friend_of_friend where friend_id=viewer) ;
end;

CREATE OR REPLACE FUNCTION sp_view_user_diary_entry_friend_of_friend_updated(
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
with my_friend as 
 (select friend_id from friendship where user_name =viewer and status=1),
friend_of_friend as (
select friend_id from friendship where user_name in 
 (select friend_id from my_friend) and status=1
 group by friend_id)
select user_name,title,body,time_posted from user_diary  where privacy = 2 and body ilike '%'||keyword||'%' and exists (select friend_id from friend_of_friend where friend_id<> viewer) ;
end;

CREATE OR REPLACE FUNCTION sp_view_user_diary_entry_friend_updated(
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
with my_friend as (
select user_name from users 
where user_name in (select friend_id from friendship where user_name=viewer or friend_id=viewer) or user_name in (select user_name from friendship where user_name=viewer or friend_id=viewer)),
with_username_diary as (
select user_name,title,body,time_posted from user_diary join users using (user_name)
where user_name in (select f.friend_id from friendship f,my_friend v where f.user_name=v.user_name or f.friend_id=v.user_name and f.user_name!=viewer or f.friend_id!=viewer) or user_name in (select f.user_name from friendship f,my_friend v where f.user_name=v.user_name or f.friend_id=v.user_name and f.user_name!=viewer or f.friend_id!=viewer))
select * from with_username_diary where user_name!=viewer and body ilike '%'||keyword||'%';
end;


CREATE OR REPLACE FUNCTION sp_view_user_diary_friend(
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
with with_user_search as (
select user_name,title,body,time_posted from user_diary u join users e using(user_name) where e.user_name in (select friend_id from friendship where user_name=viewer or friend_id =viewer and status=1 ) or e.user_name in (select user_name from friendship where user_name=viewer or friend_id =viewer and status=1 )  and u.privacy=2 )
select * from with_user_search where user_name!=viewer and body ilike '%'||keyword||'%';
end;

CREATE OR REPLACE FUNCTION sp_view_user_diary_public(IN keyword character varying)
  RETURNS TABLE(user_name_ names, title_ character varying, body_ character varying, time_posted_ timestamp without time zone) AS
$BODY$
begin 
return query
select user_name,title, body,time_posted from user_diary where privacy=3 and body ilike '%'||keyword||'%';
end;

CREATE OR REPLACE FUNCTION sp_view_user_profile_friend(
    IN _user_name names,
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, profile_ character varying) AS
$BODY$
begin 
return query
with friend_type as
(select status from friendship where user_name=_user_name and friend_id=viewer )
select user_name,profile from user_profile join users using(user_name),friend_type where user_name =_user_name and privacy = 1 and friend_type.status=1 and profile ilike '%'||keyword||'%';
end;

CREATE OR REPLACE FUNCTION sp_view_user_profile_friend_changed(
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, profile_ character varying) AS
$BODY$
begin 
return query
with with_user_search as (
select user_name,profile from user_profile join users using(user_name) where user_name in (select friend_id from friendship where user_name=viewer or friend_id =viewer and status=1 ) or user_name in (select user_name from friendship where user_name=viewer or friend_id =viewer and status=1 )  and privacy=2 )
select * from with_user_search where user_name!=viewer and profile ilike '%'||keyword||'%';
end;

CREATE OR REPLACE FUNCTION sp_view_user_profile_friend_of_friend(
    IN viewer names,
    IN keyword character varying)
  RETURNS TABLE(user_name_ names, profile_ character varying) AS
$BODY$
begin 
return query
with my_friend as (
select user_name from users 
where user_name in (select friend_id from friendship where user_name=viewer or friend_id=viewer) or user_name in (select user_name from friendship where user_name=viewer or friend_id=viewer)),
with_username_profile as (
select user_name,profile from user_profile join users using (user_name)
where user_name in (select f.friend_id from friendship f,my_friend v where f.user_name=v.user_name or f.friend_id=v.user_name and f.user_name!=viewer or f.friend_id!=viewer) or user_name in (select f.user_name from friendship f,my_friend v where f.user_name=v.user_name or f.friend_id=v.user_name and f.user_name!=viewer or f.friend_id!=viewer))
select * from with_username_profile where user_name!=viewer and profile ilike '%'||keyword||'%';
end; 

CREATE OR REPLACE FUNCTION sp_view_user_profile_public(IN keyword character varying)
  RETURNS TABLE(user_name_ names, profile_ character varying) AS
$BODY$
begin 
return query
select user_name,profile from user_profile join users using(user_name) where privacy=3 and profile ilike '%'||keyword||'%';
end;
