--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Name: is_email(character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION is_email(email character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
BEGIN
      RETURN email ~* e'^([^@\\s]+)@((?:[a-z0-9-]+\\.)+[a-z]{2,})$';
END;
$_$;


--
-- Name: email; Type: DOMAIN; Schema: public; Owner: -
--

CREATE DOMAIN email AS character varying
	CONSTRAINT valid_email CHECK (is_email(VALUE));


--
-- Name: is_url(character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION is_url(url character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
BEGIN
      RETURN url ~* e'(https?|ftps?)://((([a-z0-9-]+\\.)+[a-z]{2,6})|(\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}))(:[0-9]+)?(/\\S*)*$';
END;
$_$;


--
-- Name: url; Type: DOMAIN; Schema: public; Owner: -
--

CREATE DOMAIN url AS character varying
	CONSTRAINT valid_url CHECK (is_url(VALUE));


--
-- Name: cut_nicely(character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION cut_nicely(my_string character varying, my_length integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    DECLARE
        my_pointer INTEGER;
    BEGIN
        my_pointer := my_length;
        WHILE my_pointer < length(my_string) AND substr(my_string, my_pointer, 1) ~* '[À-ÿa-z0-9-]' LOOP
            my_pointer := my_pointer + 1;
        END LOOP;

        RETURN substr(my_string, 1, my_pointer - 1);
    END;
$$;


--
-- Name: slugify(character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION slugify(string character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    BEGIN
          RETURN trim(both '-' from regexp_replace(lower(transliterate(string::varchar)), '[^a-z0-9]+', '-', 'g'));
    END;
$$;


--
-- Name: transliterate(character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION transliterate(my_text character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    DECLARE 
      text_out VARCHAR DEFAULT '';
    BEGIN
           text_out := my_text;
           text_out := translate(text_out, 'àâäåáăąãāçċćčĉéèėëêēĕîïìíīñôöøõōùúüûūýÿỳ', 'aaaaaaaaaccccceeeeeeeiiiiinooooouuuuuyyy');
           text_out := translate(text_out, 'ÀÂÄÅÁĂĄÃĀÇĊĆČĈÉÈĖËÊĒĔÎÏÌÍĪÑÔÖØÕŌÙÚÜÛŪÝŸỲ', 'AAAAAAAAACCCCCEEEEEEEIIIIINOOOOOUUUUUYYY');
           text_out := replace(text_out, 'æ', 'ae');
           text_out := replace(text_out, 'Œ', 'OE');
           text_out := replace(text_out, 'Æ', 'AE');
           text_out := replace(text_out, 'ß', 'ss');
           text_out := replace(text_out, 'œ', 'oe');

           RETURN text_out;
    END;
$$;


--
-- Name: update_updated_at(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION update_updated_at() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
  BEGIN
    NEW.updated_at := now();

    RETURN NEW;
  END;
$$;


--
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

