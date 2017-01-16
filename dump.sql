--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.5
-- Dumped by pg_dump version 9.5.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bonus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE bonus (
    id integer NOT NULL,
    discount_percent integer NOT NULL,
    code character varying(255) NOT NULL
);


ALTER TABLE bonus OWNER TO postgres;

--
-- Name: bonus_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE bonus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE bonus_id_seq OWNER TO postgres;

--
-- Name: bonus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE bonus_id_seq OWNED BY bonus.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE orders (
    id integer NOT NULL,
    bonus_id integer,
    sum numeric(10,2) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    card character(16) NOT NULL,
    cvv character(3) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255)
);


ALTER TABLE orders OWNER TO postgres;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orders_id_seq OWNER TO postgres;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE orders_id_seq OWNED BY orders.id;


--
-- Name: orders_product; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE orders_product (
    order_id integer NOT NULL,
    product_id integer NOT NULL,
    qty integer NOT NULL
);


ALTER TABLE orders_product OWNER TO postgres;

--
-- Name: product; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE product (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    image_url character varying(255) NOT NULL,
    price numeric(10,2) NOT NULL
);


ALTER TABLE product OWNER TO postgres;

--
-- Name: product_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE product_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE product_id_seq OWNER TO postgres;

--
-- Name: product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE product_id_seq OWNED BY product.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bonus ALTER COLUMN id SET DEFAULT nextval('bonus_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orders ALTER COLUMN id SET DEFAULT nextval('orders_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY product ALTER COLUMN id SET DEFAULT nextval('product_id_seq'::regclass);


--
-- Data for Name: bonus; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO bonus (id, discount_percent, code) VALUES (1, 15, 'NEW');
INSERT INTO bonus (id, discount_percent, code) VALUES (2, 50, 'HALF');


--
-- Name: bonus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('bonus_id_seq', 2, true);


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('orders_id_seq', 1, true);


--
-- Data for Name: orders_product; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO product (id, title, image_url, price) VALUES (1, 'МУРЭН', '/images/1.jpg', 27999.00);
INSERT INTO product (id, title, image_url, price) VALUES (2, 'ТУЛЬСТА', '/images/2.jpg', 5999.00);
INSERT INTO product (id, title, image_url, price) VALUES (3, 'ПОЭНГ', '/images/3.jpg', 8999.00);
INSERT INTO product (id, title, image_url, price) VALUES (4, 'ЭКЕНЭС', '/images/4.jpg', 13999.00);
INSERT INTO product (id, title, image_url, price) VALUES (5, 'ХОЛЬМСЕЛЬ', '/images/5.jpg', 3499.00);
INSERT INTO product (id, title, image_url, price) VALUES (6, 'ВЭРМДЭ', '/images/6.jpg', 8999.00);


--
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('product_id_seq', 6, true);


--
-- Name: bonus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bonus
    ADD CONSTRAINT bonus_pkey PRIMARY KEY (id);


--
-- Name: orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: product_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- Name: orders_bonus_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_bonus_id_fk FOREIGN KEY (bonus_id) REFERENCES bonus(id);


--
-- Name: orders_product_orders_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orders_product
    ADD CONSTRAINT orders_product_orders_id_fk FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: orders_product_product_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orders_product
    ADD CONSTRAINT orders_product_product_id_fk FOREIGN KEY (product_id) REFERENCES product(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

