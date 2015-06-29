begin;
alter table pomm.news add lexem tsvector;
update
  pomm.news
set
  lexem = setweight(to_tsvector('english', title), 'A')||setweight(to_tsvector('english', content), 'C')
;
create or replace function pomm.updateNewsTsVector() returns trigger as $function$
begin
  select into NEW.lexem setweight(to_tsvector('english', NEW.title), 'A')||setweight(to_tsvector('english', NEW.content), 'C');

  return NEW;
end;
$function$ language plpgsql;
create trigger pomm_news_update_lexem before insert or update of title, content on pomm.news for each row execute procedure pomm.updateNewsTsVector();
commit;
