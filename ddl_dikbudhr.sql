--
-- PostgreSQL database dump
--

-- Dumped from database version 11.7
-- Dumped by pg_dump version 14.6

-- Started on 2025-03-10 14:53:05 WIB

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 16 (class 2615 OID 34804)
-- Name: hris; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA hris;


ALTER SCHEMA hris OWNER TO postgres;

--
-- TOC entry 15 (class 2615 OID 34812)
-- Name: webservice; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA webservice;


ALTER SCHEMA webservice OWNER TO postgres;

--
-- TOC entry 1347 (class 1247 OID 34861)
-- Name: masa_kerja_type; Type: TYPE; Schema: hris; Owner: postgres
--

CREATE TYPE hris.masa_kerja_type AS (
	r double precision,
	i double precision
);


ALTER TYPE hris.masa_kerja_type OWNER TO postgres;

--
-- TOC entry 933 (class 1255 OID 34862)
-- Name: calc_age(date); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.calc_age(param_tanggal date) RETURNS smallint
    LANGUAGE plpgsql
    AS $$BEGIN
	--Routine body goes here...

	RETURN (
			(
				date_part('year' :: TEXT,(now()) :: DATE) - date_part(
					'year' :: TEXT,
					param_tanggal
				)
			) * (12) :: DOUBLE PRECISION
		) + (
			date_part(
				'month' :: TEXT,
				(now()) :: DATE
			) - date_part(
				'month' :: TEXT,
				param_tanggal
			)
		);
END
$$;


ALTER FUNCTION hris.calc_age(param_tanggal date) OWNER TO postgres;

--
-- TOC entry 935 (class 1255 OID 34863)
-- Name: get_kgb_yad(date); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.get_kgb_yad(__tmt_cpns date) RETURNS date
    LANGUAGE plpgsql
    AS $$
	declare dt_yad date; 
	declare y_tmt int;
	declare m_tmt int;
	declare d_tmt int;
	declare y_yad int;
	declare m_yad int;
	declare d_yad int;
	BEGIN
	    y_tmt = date_part('year',__tmt_cpns);
			m_tmt = date_part('month',__tmt_cpns);
			d_tmt = date_part('day',__tmt_cpns);
			
			y_yad	= date_part('year',CURRENT_DATE);
			
			dt_yad = (y_yad||'-'||m_tmt||'-'||d_tmt)::date;	
			if(mod(y_yad,2) = mod(y_tmt,2)) then -- sama -genap 
				if(dt_yad >=current_date) then 
						return dt_yad;
				else 
						return (dt_yad + interval '2 year')::date;
				end if; 
				else 
						y_yad = y_yad +1;
						dt_yad = (y_yad||'-'||m_tmt||'-'||d_tmt)::date;	
						return dt_yad;
			end if;
	RETURN dt_yad;
END
$$;


ALTER FUNCTION hris.get_kgb_yad(__tmt_cpns date) OWNER TO postgres;

--
-- TOC entry 936 (class 1255 OID 34864)
-- Name: get_masa_kerja(date, date); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.get_masa_kerja(date_start date, date_end date) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
	declare _month varchar;
	BEGIN
	-- Routine body goes here...
	
	select  extract(year from age(date_end,date_start)) || ' thn ' || extract(month from age(date_end,date_start)) || ' bln 'into _month;
	return _month;
END
$$;


ALTER FUNCTION hris.get_masa_kerja(date_start date, date_end date) OWNER TO postgres;

--
-- TOC entry 937 (class 1255 OID 34865)
-- Name: get_masa_kerja_arr(date, date); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.get_masa_kerja_arr(date_start date, date_end date) RETURNS integer[]
    LANGUAGE plpgsql
    AS $$
	declare _month int;
	declare _year int;
	declare _json int[2];
	BEGIN
		select extract(year from age(date_end,date_start)) ,extract(month from age(date_end,date_start))
		into _year,_month;
		
		
	 _json[0]= _year;
	 _json[1]= _month;
	--select json_build_object('year'::text,extract(year from age(date_end,date_start))::int,'month'::text,extract(month from age(date_end,date_start))) into _json;
	-- select json_build_object('year',1,'month',2) into _json;
	return _json;
END
$$;


ALTER FUNCTION hris.get_masa_kerja_arr(date_start date, date_end date) OWNER TO postgres;

--
-- TOC entry 938 (class 1255 OID 34866)
-- Name: get_month_masa_kerja(date, date); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.get_month_masa_kerja(date_start date, date_end date) RETURNS smallint
    LANGUAGE plpgsql
    AS $$
	declare _month int4;
	BEGIN
	-- Routine body goes here...
	
	select  extract(year from age(date_end,date_start))*12 + extract(month from age(date_end,date_start)) into _month;
	return _month;
END
$$;


ALTER FUNCTION hris.get_month_masa_kerja(date_start date, date_end date) OWNER TO postgres;

--
-- TOC entry 939 (class 1255 OID 34867)
-- Name: uuid_generate_v1(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_generate_v1() RETURNS uuid
    LANGUAGE c STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v1';


ALTER FUNCTION hris.uuid_generate_v1() OWNER TO postgres;

--
-- TOC entry 940 (class 1255 OID 34868)
-- Name: uuid_generate_v1mc(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_generate_v1mc() RETURNS uuid
    LANGUAGE c STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v1mc';


ALTER FUNCTION hris.uuid_generate_v1mc() OWNER TO postgres;

--
-- TOC entry 941 (class 1255 OID 34869)
-- Name: uuid_generate_v3(uuid, text); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_generate_v3(namespace uuid, name text) RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v3';


ALTER FUNCTION hris.uuid_generate_v3(namespace uuid, name text) OWNER TO postgres;

--
-- TOC entry 942 (class 1255 OID 34870)
-- Name: uuid_generate_v4(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_generate_v4() RETURNS uuid
    LANGUAGE c STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v4';


ALTER FUNCTION hris.uuid_generate_v4() OWNER TO postgres;

--
-- TOC entry 943 (class 1255 OID 34871)
-- Name: uuid_generate_v5(uuid, text); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_generate_v5(namespace uuid, name text) RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v5';


ALTER FUNCTION hris.uuid_generate_v5(namespace uuid, name text) OWNER TO postgres;

--
-- TOC entry 944 (class 1255 OID 34872)
-- Name: uuid_nil(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_nil() RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_nil';


ALTER FUNCTION hris.uuid_nil() OWNER TO postgres;

--
-- TOC entry 945 (class 1255 OID 34873)
-- Name: uuid_ns_dns(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_ns_dns() RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_ns_dns';


ALTER FUNCTION hris.uuid_ns_dns() OWNER TO postgres;

--
-- TOC entry 946 (class 1255 OID 34874)
-- Name: uuid_ns_oid(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_ns_oid() RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_ns_oid';


ALTER FUNCTION hris.uuid_ns_oid() OWNER TO postgres;

--
-- TOC entry 947 (class 1255 OID 34875)
-- Name: uuid_ns_url(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_ns_url() RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_ns_url';


ALTER FUNCTION hris.uuid_ns_url() OWNER TO postgres;

--
-- TOC entry 948 (class 1255 OID 34876)
-- Name: uuid_ns_x500(); Type: FUNCTION; Schema: hris; Owner: postgres
--

CREATE FUNCTION hris.uuid_ns_x500() RETURNS uuid
    LANGUAGE c IMMUTABLE STRICT
    AS '$libdir/uuid-ossp', 'uuid_ns_x500';


ALTER FUNCTION hris.uuid_ns_x500() OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 35030)
-- Name: agama_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.agama_id_seq
    START WITH 7
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.agama_id_seq OWNER TO postgres;

SET default_tablespace = '';

--
-- TOC entry 249 (class 1259 OID 35032)
-- Name: agama; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.agama (
    "ID" smallint DEFAULT nextval('hris.agama_id_seq'::regclass) NOT NULL,
    "NAMA" character varying(20),
    "NCSISTIME" character varying(30),
    deleted smallint
);


ALTER TABLE hris.agama OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 35040)
-- Name: golongan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.golongan (
    "NAMA" character varying(255),
    "NAMA_PANGKAT" character varying(255),
    "ID" integer NOT NULL,
    "NAMA2" character varying(255),
    "GOL" smallint,
    "GOL_PPPK" character varying(255)
);


ALTER TABLE hris.golongan OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 34877)
-- Name: jabatan_No_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."jabatan_No_seq"
    START WITH 1776
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."jabatan_No_seq" OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 35050)
-- Name: jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jabatan (
    "NO" integer DEFAULT nextval('hris."jabatan_No_seq"'::regclass) NOT NULL,
    "KODE_JABATAN" character varying NOT NULL,
    "NAMA_JABATAN" character varying,
    "NAMA_JABATAN_FULL" text,
    "JENIS_JABATAN" character varying,
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(32),
    id bigint NOT NULL,
    "NAMA_JABATAN_BKN" character varying(255),
    "KATEGORI_JABATAN" character varying(100),
    "BKN_ID" character varying(36)
);


ALTER TABLE hris.jabatan OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 35061)
-- Name: jenis_diklat_fungsional_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jenis_diklat_fungsional_id_seq
    START WITH 217
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jenis_diklat_fungsional_id_seq OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 35063)
-- Name: jenis_diklat_fungsional; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_diklat_fungsional (
    "ID" bigint DEFAULT nextval('hris.jenis_diklat_fungsional_id_seq'::regclass) NOT NULL,
    "NAMA" character varying(255) DEFAULT nextval('hris.jenis_diklat_fungsional_id_seq'::regclass)
);


ALTER TABLE hris.jenis_diklat_fungsional OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 35072)
-- Name: jenis_diklat_struktural; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_diklat_struktural (
    "ID" integer NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.jenis_diklat_struktural OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 35079)
-- Name: jenis_hukuman; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_hukuman (
    "ID" character(2),
    "NAMA" character(100),
    "TINGKAT_HUKUMAN" character(1),
    "NAMA_TINGKAT_HUKUMAN" character(10)
);


ALTER TABLE hris.jenis_hukuman OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 35086)
-- Name: rwt_jenis_jabatan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_jenis_jabatan_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_jenis_jabatan_id_seq OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 35088)
-- Name: jenis_jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_jabatan (
    "ID" character varying(1) DEFAULT nextval('hris.rwt_jenis_jabatan_id_seq'::regclass) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.jenis_jabatan OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 35096)
-- Name: jenis_kawin; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_kawin (
    "ID" character varying(255) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.jenis_kawin OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 35106)
-- Name: jenis_pegawai; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_pegawai (
    "ID" character varying(5) NOT NULL,
    "NAMA" character varying(200)
);


ALTER TABLE hris.jenis_pegawai OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 35113)
-- Name: jenis_penghargaan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_penghargaan (
    "ID" character(3) NOT NULL,
    "NAMA" character(100)
);


ALTER TABLE hris.jenis_penghargaan OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 35120)
-- Name: kedudukan_hukum; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kedudukan_hukum (
    "ID" character varying(4) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.kedudukan_hukum OWNER TO postgres;

--
-- TOC entry 273 (class 1259 OID 35127)
-- Name: lokasi; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.lokasi (
    "ID" character varying(255) NOT NULL,
    "KANREG_ID" character varying(255),
    "LOKASI_ID" character varying(255),
    "NAMA" character varying(255),
    "JENIS" character varying(255),
    "JENIS_KABUPATEN" character varying(255),
    "JENIS_DESA" character varying(255),
    "IBUKOTA" character varying(255)
);


ALTER TABLE hris.lokasi OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 35137)
-- Name: pendidikan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pendidikan (
    "ID" character varying(255) NOT NULL,
    "TINGKAT_PENDIDIKAN_ID" character varying(255),
    "NAMA" character varying(255),
    "CEPAT_KODE" character varying(255)
);


ALTER TABLE hris.pendidikan OWNER TO postgres;

--
-- TOC entry 277 (class 1259 OID 35147)
-- Name: tkpendidikan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tkpendidikan (
    "ID" character varying(255) NOT NULL,
    "GOLONGAN_ID" character varying(255),
    "NAMA" character varying(255),
    "GOLONGAN_AWAL_ID" character varying(255),
    "DELETED" smallint,
    "ABBREVIATION" character varying(255),
    "TINGKAT" smallint
);


ALTER TABLE hris.tkpendidikan OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 35157)
-- Name: unitkerja; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255) NOT NULL,
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint DEFAULT 0 NOT NULL,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32),
    "WAKTU" character varying(4),
    "PERATURAN" character varying(100)
);


ALTER TABLE hris.unitkerja OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 34899)
-- Name: pegawai_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.pegawai_id_seq
    START WITH 32781
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.pegawai_id_seq OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 35169)
-- Name: pegawai; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pegawai (
    "ID" integer DEFAULT nextval('hris.pegawai_id_seq'::regclass) NOT NULL,
    "PNS_ID" character varying(36) NOT NULL,
    "NIP_LAMA" character varying(9),
    "NIP_BARU" character varying(18),
    "NAMA" character varying(255),
    "GELAR_DEPAN" character varying(60),
    "GELAR_BELAKANG" character varying(60),
    "TEMPAT_LAHIR_ID" character varying(50),
    "TGL_LAHIR" date,
    "JENIS_KELAMIN" character varying(10),
    "AGAMA_ID" integer,
    "JENIS_KAWIN_ID" character varying(255),
    "NIK" character varying(255),
    "NOMOR_DARURAT" character varying(255),
    "NOMOR_HP" character varying(60),
    "EMAIL" character varying(255),
    "ALAMAT" character varying(255),
    "NPWP" character varying(255),
    "BPJS" character varying(50),
    "JENIS_PEGAWAI_ID" character varying(50),
    "KEDUDUKAN_HUKUM_ID" character varying(36),
    "STATUS_CPNS_PNS" character varying(20),
    "KARTU_PEGAWAI" character varying(30),
    "NOMOR_SK_CPNS" character varying(60),
    "TGL_SK_CPNS" date,
    "TMT_CPNS" date,
    "TMT_PNS" date,
    "GOL_AWAL_ID" character varying(36),
    "GOL_ID" integer,
    "TMT_GOLONGAN" date,
    "MK_TAHUN" character varying(20),
    "MK_BULAN" character varying(20),
    "JENIS_JABATAN_IDx" character varying(36),
    "JABATAN_ID" character varying(36),
    "TMT_JABATAN" date,
    "PENDIDIKAN_ID" character varying(36),
    "TAHUN_LULUS" character varying(20),
    "KPKN_ID" character varying(36),
    "LOKASI_KERJA_ID" character varying(36),
    "UNOR_ID" character varying(36),
    "UNOR_INDUK_ID" character varying(36),
    "INSTANSI_INDUK_ID" character varying(36),
    "INSTANSI_KERJA_ID" character varying(36),
    "SATUAN_KERJA_INDUK_ID" character varying(36),
    "SATUAN_KERJA_KERJA_ID" character varying(36),
    "GOLONGAN_DARAH" character varying(20),
    "PHOTO" character varying(100),
    "TMT_PENSIUN" date,
    "LOKASI_KERJA" character(200),
    "JML_ISTRI" character(1),
    "JML_ANAK" character(1),
    "NO_SURAT_DOKTER" character(100),
    "TGL_SURAT_DOKTER" date,
    "NO_BEBAS_NARKOBA" character(100),
    "TGL_BEBAS_NARKOBA" date,
    "NO_CATATAN_POLISI" character(100),
    "TGL_CATATAN_POLISI" date,
    "AKTE_KELAHIRAN" character(50),
    "STATUS_HIDUP" character(15),
    "AKTE_MENINGGAL" character(50),
    "TGL_MENINGGAL" date,
    "NO_ASKES" character(50),
    "NO_TASPEN" character(50),
    "TGL_NPWP" date,
    "TEMPAT_LAHIR" character(200),
    "PENDIDIKAN" character(165),
    "TK_PENDIDIKAN" character(3),
    "TEMPAT_LAHIR_NAMA" character(200),
    "JENIS_JABATAN_NAMA" character(200),
    "JABATAN_NAMA" character(254),
    "KPKN_NAMA" character(255),
    "INSTANSI_INDUK_NAMA" character(100),
    "INSTANSI_KERJA_NAMA" character(160),
    "SATUAN_KERJA_INDUK_NAMA" character(170),
    "SATUAN_KERJA_NAMA" character(155),
    "JABATAN_INSTANSI_ID" character(15),
    "BUP" smallint DEFAULT 58,
    "JABATAN_INSTANSI_NAMA" character varying(512) DEFAULT NULL::character varying,
    "JENIS_JABATAN_ID" integer,
    terminated_date date,
    status_pegawai smallint DEFAULT 1,
    "JABATAN_PPNPN" character(255),
    "JABATAN_INSTANSI_REAL_ID" character(36),
    "CREATED_DATE" date,
    "CREATED_BY" integer,
    "UPDATED_DATE" date,
    "UPDATED_BY" integer,
    "EMAIL_DIKBUD_BAK" character varying(255),
    "EMAIL_DIKBUD" character varying(100),
    "KODECEPAT" character varying(100),
    "IS_DOSEN" smallint,
    "MK_TAHUN_SWASTA" smallint DEFAULT 0,
    "MK_BULAN_SWASTA" smallint DEFAULT 0,
    "KK" character varying(30),
    "NIDN" character varying(30),
    "KET" character varying(255),
    "NO_SK_PEMBERHENTIAN" character varying(100),
    status_pegawai_backup smallint,
    "MASA_KERJA" character varying,
    "KARTU_ASN" character varying
);


ALTER TABLE hris.pegawai OWNER TO postgres;

--
-- TOC entry 6102 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN pegawai.status_pegawai; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.pegawai.status_pegawai IS '1=pns,2=honorer';


--
-- TOC entry 233 (class 1259 OID 34955)
-- Name: rwt_diklat_fungsional_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_diklat_fungsional_ID_seq"
    START WITH 7
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_diklat_fungsional_ID_seq" OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 35184)
-- Name: rwt_diklat_fungsional; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_diklat_fungsional (
    "DIKLAT_FUNGSIONAL_ID" character varying(255) DEFAULT nextval('hris."rwt_diklat_fungsional_ID_seq"'::regclass) NOT NULL,
    "NIP_BARU" character varying(255),
    "NIP_LAMA" character varying(255),
    "JENIS_DIKLAT" character varying(255),
    "NAMA_KURSUS" character varying(255),
    "JUMLAH_JAM" character varying(255),
    "TAHUN" character varying(255),
    "INSTITUSI_PENYELENGGARA" character varying(255),
    "JENIS_KURSUS_SERTIPIKAT" character varying(255),
    "NOMOR_SERTIPIKAT" character varying(255),
    "INSTANSI" character varying(255),
    "STATUS_DATA" character varying(255),
    "TANGGAL_KURSUS" date,
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(200),
    "LAMA" real,
    "SIASN_ID" character varying
);


ALTER TABLE hris.rwt_diklat_fungsional OWNER TO postgres;

--
-- TOC entry 283 (class 1259 OID 35191)
-- Name: unitkerja_1234; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_1234 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255) NOT NULL,
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint DEFAULT 0 NOT NULL,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255)
);


ALTER TABLE hris.unitkerja_1234 OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 34964)
-- Name: rwt_diklat_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_diklat_ID_seq"
    START WITH 37
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_diklat_ID_seq" OWNER TO postgres;

--
-- TOC entry 284 (class 1259 OID 35203)
-- Name: rwt_diklat_struktural; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_diklat_struktural (
    "ID" character varying(255) DEFAULT nextval('hris."rwt_diklat_ID_seq"'::regclass) NOT NULL,
    "PNS_ID" character varying(255),
    "PNS_NIP" character varying(255),
    "PNS_NAMA" character varying(255),
    "ID_DIKLAT" character varying(255),
    "NAMA_DIKLAT" character varying(255),
    "NOMOR" character varying(255),
    "TANGGAL" date,
    "TAHUN" smallint,
    "STATUS_DATA" character varying(15),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(200),
    "LAMA" real,
    "CREATED_DATE" timestamp without time zone DEFAULT now(),
    "SIASN_ID" character varying
);


ALTER TABLE hris.rwt_diklat_struktural OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 34973)
-- Name: rwt_golongan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_golongan_id_seq
    START WITH 351278
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_golongan_id_seq OWNER TO postgres;

--
-- TOC entry 285 (class 1259 OID 35215)
-- Name: rwt_golongan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_golongan (
    "ID" character varying(255) DEFAULT nextval('hris.rwt_golongan_id_seq'::regclass) NOT NULL,
    "PNS_ID" character varying(255),
    "PNS_NIP" character varying(255),
    "PNS_NAMA" character varying(255),
    "KODE_JENIS_KP" character varying(255),
    "JENIS_KP" character varying(255),
    "ID_GOLONGAN" character varying(255),
    "GOLONGAN" character varying(255),
    "PANGKAT" character varying(255),
    "SK_NOMOR" character varying(255),
    "NOMOR_BKN" character varying(255),
    "JUMLAH_ANGKA_KREDIT_UTAMA" character varying(255),
    "JUMLAH_ANGKA_KREDIT_TAMBAHAN" character varying(255),
    "MK_GOLONGAN_TAHUN" character varying(255),
    "MK_GOLONGAN_BULAN" character varying(255),
    "SK_TANGGAL" date,
    "TANGGAL_BKN" date,
    "TMT_GOLONGAN" date,
    "STATUS_SATKER" integer,
    "STATUS_BIRO" integer,
    "PANGKAT_TERAKHIR" integer,
    "ID_BKN" character varying(36),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255),
    id_arsip bigint,
    "GOLONGAN_ASAL" character varying(2),
    "BASIC" character varying(15),
    "SK_TYPE" smallint,
    "KANREG" character varying(5),
    "KPKN" character varying(50),
    "KETERANGAN" character varying(255),
    "LPNK" character varying(10),
    "JENIS_RIWAYAT" character varying(50)
);


ALTER TABLE hris.rwt_golongan OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 34982)
-- Name: rwt_jabatan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_jabatan_ID_seq"
    START WITH 94276
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_jabatan_ID_seq" OWNER TO postgres;

--
-- TOC entry 826 (class 1259 OID 1778184)
-- Name: rwt_jabatan_empty; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_jabatan_empty (
    "ID_BKN" character(64),
    "PNS_ID" character(100),
    "PNS_NIP" character(25),
    "PNS_NAMA" character(200),
    "ID_UNOR" character(100),
    "UNOR" text,
    "ID_JENIS_JABATAN" character(10),
    "JENIS_JABATAN" character(250),
    "ID_JABATAN" character(100),
    "NAMA_JABATAN" text,
    "ID_ESELON" character(32),
    "ESELON" character(100),
    "TMT_JABATAN" date,
    "NOMOR_SK" character(100),
    "TANGGAL_SK" date,
    "ID_SATUAN_KERJA" character(32),
    "TMT_PELANTIKAN" date,
    "IS_ACTIVE" character(1),
    "ESELON1" text,
    "ESELON2" text,
    "ESELON3" text,
    "ESELON4" text,
    "ID" bigint DEFAULT nextval('hris."rwt_jabatan_ID_seq"'::regclass) NOT NULL,
    "CATATAN" character(255),
    "JENIS_SK" character(100),
    "LAST_UPDATED" date,
    "STATUS_SATKER" integer,
    "STATUS_BIRO" integer,
    "ID_JABATAN_BKN" character(64),
    "ID_UNOR_BKN" character(32),
    "JABATAN_TERAKHIR" integer,
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255),
    "ID_TABEL_MUTASI" bigint,
    "TERMINATED_DATE" date
);


ALTER TABLE hris.rwt_jabatan_empty OWNER TO postgres;

--
-- TOC entry 287 (class 1259 OID 35239)
-- Name: rwt_pekerjaan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_pekerjaan_ID_seq"
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_pekerjaan_ID_seq" OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 35241)
-- Name: rwt_pekerjaan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_pekerjaan (
    "ID" integer DEFAULT nextval('hris."rwt_pekerjaan_ID_seq"'::regclass) NOT NULL,
    "PNS_NIP" character(30),
    "JENIS_PERUSAHAAN" character(100),
    "NAMA_PERUSAHAAN" character(200),
    "SEBAGAI" character(200),
    "DARI_TANGGAL" date,
    "SAMPAI_TANGGAL" date,
    "PNS_ID" character(32),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255)
);


ALTER TABLE hris.rwt_pekerjaan OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 35005)
-- Name: rwt_pendidikan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_pendidikan_ID_seq"
    START WITH 66872
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_pendidikan_ID_seq" OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 35253)
-- Name: rwt_pendidikan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_pendidikan (
    "ID" integer DEFAULT nextval('hris."rwt_pendidikan_ID_seq"'::regclass) NOT NULL,
    "PNS_ID_3" character varying(32),
    "TINGKAT_PENDIDIKAN_ID" character varying(32),
    "PENDIDIKAN_ID_3" character varying(32),
    "TANGGAL_LULUS" date,
    "NOMOR_IJASAH" character varying(100),
    "NAMA_SEKOLAH" character varying(200),
    "GELAR_DEPAN" character varying(50),
    "GELAR_BELAKANG" character varying(60),
    "PENDIDIKAN_PERTAMA" character varying(1),
    "NEGARA_SEKOLAH" character varying(255),
    "TAHUN_LULUS" character varying(4),
    "NIP" character(35),
    "DIAKUI_BKN" integer,
    "TUGAS_BELAJAR" character(255),
    "STATUS_SATKER" integer,
    "STATUS_BIRO" integer,
    "PENDIDIKAN_TERAKHIR" integer,
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(200),
    "PNS_ID" character varying(255) NOT NULL,
    "PENDIDIKAN_ID" character varying
);


ALTER TABLE hris.rwt_pendidikan OWNER TO postgres;

--
-- TOC entry 290 (class 1259 OID 35265)
-- Name: rwt_prestasi_kerja_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_prestasi_kerja_id_seq
    START WITH 16
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_prestasi_kerja_id_seq OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 35267)
-- Name: rwt_prestasi_kerja; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_prestasi_kerja (
    "ID" character varying(255) DEFAULT nextval('hris.rwt_prestasi_kerja_id_seq'::regclass) NOT NULL,
    "PNS_NIP" character varying(255),
    "PNS_NAMA" character varying(255),
    "ATASAN_LANGSUNG_PNS_NAMA" character varying(255),
    "ATASAN_LANGSUNG_PNS_NIP" character varying(255),
    "NILAI_SKP" character varying(255),
    "NILAI_PROSENTASE_SKP" character varying(255),
    "NILAI_SKP_AKHIR" character varying(255),
    "PERILAKU_KOMITMEN" character varying(255),
    "PERILAKU_INTEGRITAS" character varying(255),
    "PERILAKU_DISIPLIN" character varying(255),
    "PERILAKU_KERJASAMA" character varying(255),
    "PERILAKU_ORIENTASI_PELAYANAN" character varying(255),
    "PERILAKU_KEPEMIMPINAN" character varying(255),
    "NILAI_PERILAKU" character varying(255),
    "NILAI_PROSENTASE_PERILAKU" character varying(255),
    "NILAI_PERILAKU_AKHIR" character varying(255),
    "NILAI_PPK" character varying(255),
    "TAHUN" integer,
    "JABATAN_TIPE" character varying(255),
    "PNS_ID" character varying(255),
    "ATASAN_LANGSUNG_PNS_ID" character varying(255),
    "ATASAN_ATASAN_LANGSUNG_PNS_ID" character varying(255),
    "ATASAN_ATASAN_LANGSUNG_PNS_NAMA" character varying(255),
    "ATASAN_ATASAN_LANGSUNG_PNS_NIP" character varying(255),
    "JABATAN_TIPE_TEXT" character varying(255),
    "ATASAN_LANGSUNG_PNS_JABATAN" character varying(255),
    "ATASAN_ATASAN_LANGSUNG_PNS_JABATAN" character varying(255),
    "JABATAN_NAMA" character varying(255),
    "BKN_ID" character varying(36),
    "UNOR_PENILAI" character varying(200),
    "UNOR_ATASAN_PENILAI" character varying(200),
    "ATASAN_PENILAI_PNS" character varying(200),
    "PENILAI_PNS" character varying(200),
    "GOL_PENILAI" character varying(20),
    "GOL_ATASAN_PENILAI" character varying(20),
    "TMT_GOL_PENILAI" character varying(20),
    "TMT_GOL_ATASAN_PENILAI" character varying(255),
    "PERATURAN" character varying(20),
    created_date date,
    updated_date date,
    "PERILAKU_INISIATIF_KERJA" character varying(20)
);


ALTER TABLE hris.rwt_prestasi_kerja OWNER TO postgres;

--
-- TOC entry 487 (class 1259 OID 36278)
-- Name: tbl_file_ds; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds (
    id_file character varying(200) NOT NULL,
    waktu_buat timestamp(0) without time zone,
    kategori character varying(100),
    teks_base64 text,
    id_pegawai_ttd character varying(255),
    is_signed smallint,
    nip_sk character varying(50),
    nomor_sk character varying(50),
    tgl_sk date,
    tmt_sk date,
    lokasi_file text,
    is_corrected smallint,
    catatan text,
    id_pegawai_korektor character varying(100),
    asal_surat_sk character varying(100),
    is_returned smallint,
    nama_pemilik_sk character varying(200),
    jabatan_pemilik_sk text,
    teks_base64_sign text,
    unit_kerja_pemilik_sk text,
    id integer NOT NULL,
    nip_pemroses character varying(50),
    ds_ok smallint,
    arsip character varying(50),
    "PNS_NONPNS" character varying(20),
    tmt_sampai_dengan date,
    telah_kirim smallint,
    halaman_ttd smallint DEFAULT 1,
    show_qrcode smallint DEFAULT 0,
    letak_ttd smallint DEFAULT 0,
    kode_unit_kerja_internal character varying(200),
    kode_jabatan_internal character varying(200),
    kelompok_jabatan character varying(200),
    tgl_tandatangan timestamp(6) without time zone,
    email_kirim character varying(200),
    sent_to_siasin character varying(100) DEFAULT 'n'::character varying,
    blockchain_issuer_id character varying,
    blockchain_image_url character varying,
    blockchain_hash character varying
);


ALTER TABLE hris.tbl_file_ds OWNER TO postgres;

--
-- TOC entry 6111 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.tmt_sampai_dengan; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.tmt_sampai_dengan IS 'khusus untuk Surat Perintah PLT/PLH';


--
-- TOC entry 6112 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.telah_kirim; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.telah_kirim IS 'Jika 1, tampilkan di dikbudHR';


--
-- TOC entry 6113 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.halaman_ttd; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.halaman_ttd IS 'halaman diletakan tandataangan digital';


--
-- TOC entry 6114 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.show_qrcode; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.show_qrcode IS '0/null : tidak tampilkan (seperti semula), 1 : tampilkan qrdari bssn';


--
-- TOC entry 6115 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.letak_ttd; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.letak_ttd IS '1:tengah bawah, 2 : kiri Bawah 0: kanan bawah';


--
-- TOC entry 6116 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.kode_unit_kerja_internal; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.kode_unit_kerja_internal IS 'untuk menampung nama unit kerja internal via kode';


--
-- TOC entry 6117 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.kode_jabatan_internal; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.kode_jabatan_internal IS 'untuk menampung nama jabatan dengan kode jabatan internal';


--
-- TOC entry 6118 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.kelompok_jabatan; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.kelompok_jabatan IS 'khusus untuk keperluan laporan rekap';


--
-- TOC entry 6119 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.tgl_tandatangan; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.tgl_tandatangan IS 'untuk mengetahui tgl tandatangan';


--
-- TOC entry 6120 (class 0 OID 0)
-- Dependencies: 487
-- Name: COLUMN tbl_file_ds.email_kirim; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds.email_kirim IS 'Untuk menentukan alamat alternatif pengiriman dokumen';


--
-- TOC entry 509 (class 1259 OID 36440)
-- Name: vw_unor_satker; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker AS
 SELECT a."ID" AS "ID_UNOR",
    a."UNOR_INDUK" AS "ID_SATKER",
    a."NAMA_UNOR",
    b."NAMA_UNOR" AS "NAMA_SATKER",
    c."NAMA_UNOR_ESELON_1",
    a."EXPIRED_DATE",
    c.id_eselon_1 AS "ID_ESELON_1"
   FROM ((hris.unitkerja a
     JOIN hris.unitkerja b ON (((a."UNOR_INDUK")::text = (b."ID")::text)))
     JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    unitkerja."ID" AS id_eselon_1,
                    unitkerja."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a_1."ID",
                    r_1.id_eselon_1,
                    r_1."NAMA_UNOR_ESELON_1"
                   FROM (hris.unitkerja a_1
                     JOIN r r_1 ON (((a_1."DIATASAN_ID")::text = (r_1."ID")::text)))
                )
         SELECT r."ID",
            r.id_eselon_1,
            r."NAMA_UNOR_ESELON_1"
           FROM r) c ON (((a."ID")::text = (c."ID")::text)));


ALTER TABLE hris.vw_unor_satker OWNER TO postgres;

--
-- TOC entry 6122 (class 0 OID 0)
-- Dependencies: 509
-- Name: VIEW vw_unor_satker; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON VIEW hris.vw_unor_satker IS 'Untuk Melihat Daftar Unit Kerja Berdasarkan Satkernya';


--
-- TOC entry 382 (class 1259 OID 35845)
-- Name: rwt_nine_box; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_nine_box (
    "ID" integer NOT NULL,
    "PNS_NIP" character varying(32),
    "NAMA" character varying(200),
    "NAMA_JABATAN" character varying(200),
    "KELAS_JABATAN" smallint,
    "KESIMPULAN" character varying(255),
    "TAHUN" character varying(4)
);


ALTER TABLE hris.rwt_nine_box OWNER TO postgres;

--
-- TOC entry 383 (class 1259 OID 35851)
-- Name: NINE_BOX_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."NINE_BOX_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."NINE_BOX_ID_seq" OWNER TO postgres;

--
-- TOC entry 6125 (class 0 OID 0)
-- Dependencies: 383
-- Name: NINE_BOX_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."NINE_BOX_ID_seq" OWNED BY hris.rwt_nine_box."ID";


--
-- TOC entry 736 (class 1259 OID 1580530)
-- Name: __temp_views; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.__temp_views (
    schemaname text,
    viewname text,
    definition text,
    level double precision
);


ALTER TABLE hris.__temp_views OWNER TO postgres;

--
-- TOC entry 650 (class 1259 OID 264121)
-- Name: absen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.absen (
    "ID" integer NOT NULL,
    "NIP" character varying(30),
    "NAMA" character varying(200),
    "TANGGAL" date,
    "JAM" character varying(20),
    "VERIFIKASI" smallint DEFAULT 0,
    latitude character varying(30),
    longitude character varying(30),
    inside_office smallint,
    input_type smallint,
    keterangan character varying(255),
    is_wfo smallint,
    timezoned character varying(100),
    waktu timestamp(6) without time zone,
    updated_at timestamp(6) without time zone,
    created_at timestamp(6) without time zone
);


ALTER TABLE hris.absen OWNER TO postgres;

--
-- TOC entry 6126 (class 0 OID 0)
-- Dependencies: 650
-- Name: COLUMN absen."VERIFIKASI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.absen."VERIFIKASI" IS '1=veririkasi ok, 0 : blm verifikasi';


--
-- TOC entry 6127 (class 0 OID 0)
-- Dependencies: 650
-- Name: COLUMN absen.input_type; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.absen.input_type IS '2 untuk pake browser';


--
-- TOC entry 648 (class 1259 OID 264117)
-- Name: absen_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."absen_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."absen_ID_seq" OWNER TO postgres;

--
-- TOC entry 6129 (class 0 OID 0)
-- Dependencies: 648
-- Name: absen_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."absen_ID_seq" OWNED BY hris.absen."ID";


--
-- TOC entry 384 (class 1259 OID 35853)
-- Name: activities_activity_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.activities_activity_id_seq
    START WITH 52450
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.activities_activity_id_seq OWNER TO postgres;

--
-- TOC entry 385 (class 1259 OID 35855)
-- Name: activities; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.activities (
    activity_id integer DEFAULT nextval('hris.activities_activity_id_seq'::regclass) NOT NULL,
    user_id bigint DEFAULT (0)::bigint NOT NULL,
    activity text NOT NULL,
    module character varying(255) NOT NULL,
    created_on timestamp(0) without time zone,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE hris.activities OWNER TO postgres;

--
-- TOC entry 386 (class 1259 OID 35864)
-- Name: anak; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.anak (
    "ID" bigint NOT NULL,
    "PASANGAN" bigint,
    "NAMA" character varying(255),
    "JENIS_KELAMIN" character varying(1),
    "TANGGAL_LAHIR" date,
    "TEMPAT_LAHIR" character varying(255),
    "STATUS_ANAK" character varying(1),
    "PNS_ID" character varying(32),
    "NIP" character varying(30)
);


ALTER TABLE hris.anak OWNER TO postgres;

--
-- TOC entry 387 (class 1259 OID 35870)
-- Name: anak_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."anak_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."anak_ID_seq" OWNER TO postgres;

--
-- TOC entry 6132 (class 0 OID 0)
-- Dependencies: 387
-- Name: anak_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."anak_ID_seq" OWNED BY hris.anak."ID";


--
-- TOC entry 623 (class 1259 OID 213011)
-- Name: arsip; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.arsip (
    "ID" integer NOT NULL,
    "ID_JENIS_ARSIP" integer,
    "NIP" character varying(25),
    "KETERANGAN" character varying(255),
    "EXTENSION_FILE" character varying(100),
    "JENIS_FILE" character varying(100),
    "FILE_SIZE" character varying(20),
    "FILE_BASE64" text,
    "CREATED_BY" integer,
    "CREATED_DATE" date,
    "UPDATED_BY" integer,
    "UPDATED_DATE" date,
    "ISVALID" integer DEFAULT 0,
    location character varying(255),
    name character varying(255),
    sk_number character varying(100),
    ref uuid DEFAULT hris.uuid_generate_v4()
);


ALTER TABLE hris.arsip OWNER TO postgres;

--
-- TOC entry 6133 (class 0 OID 0)
-- Dependencies: 623
-- Name: COLUMN arsip."EXTENSION_FILE"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.arsip."EXTENSION_FILE" IS '.doc, .xls, dll';


--
-- TOC entry 6134 (class 0 OID 0)
-- Dependencies: 623
-- Name: COLUMN arsip."JENIS_FILE"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.arsip."JENIS_FILE" IS 'image, document, zip, pdf';


--
-- TOC entry 621 (class 1259 OID 213007)
-- Name: arsip_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."arsip_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."arsip_ID_seq" OWNER TO postgres;

--
-- TOC entry 6136 (class 0 OID 0)
-- Dependencies: 621
-- Name: arsip_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."arsip_ID_seq" OWNED BY hris.arsip."ID";


--
-- TOC entry 864 (class 1259 OID 2425313)
-- Name: asesmen_hasil_asesmen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.asesmen_hasil_asesmen (
    id bigint NOT NULL,
    nip character varying,
    jenis_asesmen_jabatan character varying,
    satuan_kerja character varying,
    tanggal_asesmen date,
    jpm numeric,
    integritas numeric,
    kerjasama numeric,
    komunikasi numeric,
    orientasi_pada_hasil numeric,
    pelayanan_publik numeric,
    pengembangan_diri_dan_orang_lain numeric,
    mengelola_perubahan numeric,
    pengambilan_keputusan numeric,
    perekat_bangsa numeric
);


ALTER TABLE hris.asesmen_hasil_asesmen OWNER TO postgres;

--
-- TOC entry 863 (class 1259 OID 2425311)
-- Name: asesmen_hasil_asesmen_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.asesmen_hasil_asesmen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.asesmen_hasil_asesmen_id_seq OWNER TO postgres;

--
-- TOC entry 6137 (class 0 OID 0)
-- Dependencies: 863
-- Name: asesmen_hasil_asesmen_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.asesmen_hasil_asesmen_id_seq OWNED BY hris.asesmen_hasil_asesmen.id;


--
-- TOC entry 862 (class 1259 OID 2425297)
-- Name: asesmen_pegawai_berpotensi_jpt; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.asesmen_pegawai_berpotensi_jpt (
    id bigint NOT NULL,
    nip character varying,
    usia character varying,
    status_kepegawaian character varying,
    golongan character varying,
    jenis_jabatan character varying,
    jabatan character varying,
    tmt character varying,
    lama_jabatan_terakhir character varying,
    eselon character varying,
    satker character varying,
    unit_organisasi_induk character varying,
    kedudukan character varying,
    tipe character varying,
    pendidikan character varying,
    jabatan_madya_lain character varying,
    tmt_jabatan_madya_lain character varying,
    jabatan_struktural_lain character varying,
    tmt_jabatan_struktural_lain character varying,
    lama_menjabat_akumulasi bigint,
    rekam_jejak text,
    skp text,
    asesmen character varying,
    hukuman_disiplin character varying,
    jabatan_struktural_lainnya_json text,
    nama character varying
);


ALTER TABLE hris.asesmen_pegawai_berpotensi_jpt OWNER TO postgres;

--
-- TOC entry 861 (class 1259 OID 2425295)
-- Name: asesmen_pegawai_berpotensi_jpt_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.asesmen_pegawai_berpotensi_jpt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.asesmen_pegawai_berpotensi_jpt_id_seq OWNER TO postgres;

--
-- TOC entry 6138 (class 0 OID 0)
-- Dependencies: 861
-- Name: asesmen_pegawai_berpotensi_jpt_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.asesmen_pegawai_berpotensi_jpt_id_seq OWNED BY hris.asesmen_pegawai_berpotensi_jpt.id;


--
-- TOC entry 860 (class 1259 OID 2425286)
-- Name: asesmen_riwayat_hukuman_disiplin; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.asesmen_riwayat_hukuman_disiplin (
    id bigint NOT NULL,
    nip character varying,
    tingkat_hukuman_disiplin character varying,
    jenis_hukuman_disiplin character varying,
    no_sk character varying,
    tanggal_sk character varying,
    status character varying,
    tahun integer,
    alasan text
);


ALTER TABLE hris.asesmen_riwayat_hukuman_disiplin OWNER TO postgres;

--
-- TOC entry 859 (class 1259 OID 2425284)
-- Name: asesmen_riwayat_hukuman_disiplin_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.asesmen_riwayat_hukuman_disiplin_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.asesmen_riwayat_hukuman_disiplin_id_seq OWNER TO postgres;

--
-- TOC entry 6139 (class 0 OID 0)
-- Dependencies: 859
-- Name: asesmen_riwayat_hukuman_disiplin_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.asesmen_riwayat_hukuman_disiplin_id_seq OWNED BY hris.asesmen_riwayat_hukuman_disiplin.id;


--
-- TOC entry 388 (class 1259 OID 35872)
-- Name: backup_jabatan_23_mar_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.backup_jabatan_23_mar_2020 (
    "NIP_BARU" character varying(18),
    "UNOR_ID" character varying(32),
    "JABATAN_INSTANSI_ID" character(15),
    "JABATAN_INSTANSI_REAL_ID" character(15)
);


ALTER TABLE hris.backup_jabatan_23_mar_2020 OWNER TO postgres;

--
-- TOC entry 661 (class 1259 OID 304821)
-- Name: backup_unor_induk_13_nov_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.backup_unor_induk_13_nov_2020 (
    "ID" character varying(255),
    "UNOR_INDUK" character varying(255)
);


ALTER TABLE hris.backup_unor_induk_13_nov_2020 OWNER TO postgres;

--
-- TOC entry 627 (class 1259 OID 241478)
-- Name: backup_unor_jabatan_01_09_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.backup_unor_jabatan_01_09_2020 (
    "PNS_ID" character varying(32),
    "NIP_BARU" character varying(18),
    "UNOR_ID" character varying(32),
    "JABATAN_INSTANSI_ID" character(15),
    "JABATAN_INSTANSI_REAL_ID" character(15)
);


ALTER TABLE hris.backup_unor_jabatan_01_09_2020 OWNER TO postgres;

--
-- TOC entry 389 (class 1259 OID 35875)
-- Name: baperjakat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.baperjakat (
    "ID" integer NOT NULL,
    "TANGGAL" date NOT NULL,
    "KETERANGAN" character varying(50),
    "TANGGAL_PENETAPAN" date,
    "NO_SK_PENETAPAN" character varying(20),
    "STATUS_AKTIF" integer,
    "TANGGAL_PELANTIKAN" date
);


ALTER TABLE hris.baperjakat OWNER TO postgres;

--
-- TOC entry 390 (class 1259 OID 35878)
-- Name: baperjakat_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."baperjakat_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."baperjakat_ID_seq" OWNER TO postgres;

--
-- TOC entry 6144 (class 0 OID 0)
-- Dependencies: 390
-- Name: baperjakat_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."baperjakat_ID_seq" OWNED BY hris.baperjakat."ID";


--
-- TOC entry 391 (class 1259 OID 35880)
-- Name: ci3_sessions; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.ci3_sessions (
    ip_address character varying(100),
    "timestamp" bigint,
    data text,
    id character(50) NOT NULL
);


ALTER TABLE hris.ci3_sessions OWNER TO postgres;

--
-- TOC entry 719 (class 1259 OID 994468)
-- Name: vw_unit_list; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.vw_unit_list AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(32) AS "ESELON_1",
    (x.eselon[2])::character varying(32) AS "ESELON_2",
    (x.eselon[3])::character varying(32) AS "ESELON_3",
    (x.eselon[4])::character varying(32) AS "ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WHERE (uk."EXPIRED_DATE" IS NULL)
  WITH NO DATA;


ALTER TABLE hris.vw_unit_list OWNER TO postgres;

--
-- TOC entry 743 (class 1259 OID 1777588)
-- Name: daftar_pegawai; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.daftar_pegawai AS
 SELECT pegawai."ID",
    pegawai."PNS_ID",
    pegawai."NIP_LAMA",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."KEDUDUKAN_HUKUM_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."KARTU_PEGAWAI",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."GOL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."JENIS_JABATAN_IDx" AS "JENIS_JABATAN_ID",
    pegawai."JABATAN_ID",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."TAHUN_LULUS",
    pegawai."KPKN_ID",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."PHOTO",
    pegawai."TMT_PENSIUN",
    pegawai."LOKASI_KERJA",
    pegawai."NO_SURAT_DOKTER",
    pegawai."JML_ISTRI",
    pegawai."JML_ANAK",
    pegawai."TGL_SURAT_DOKTER",
    pegawai."NO_BEBAS_NARKOBA",
    pegawai."TGL_BEBAS_NARKOBA",
    pegawai."NO_CATATAN_POLISI",
    pegawai."TGL_CATATAN_POLISI",
    pegawai."AKTE_KELAHIRAN",
    pegawai."STATUS_HIDUP",
    pegawai."AKTE_MENINGGAL",
    pegawai."TGL_MENINGGAL",
    pegawai."NO_ASKES",
    pegawai."NO_TASPEN",
    pegawai."TGL_NPWP",
    pegawai."TEMPAT_LAHIR",
    pegawai."PENDIDIKAN",
    pegawai."TK_PENDIDIKAN",
    pegawai."TEMPAT_LAHIR_NAMA",
    pegawai."JENIS_JABATAN_NAMA",
    pegawai."JABATAN_NAMA",
    pegawai."KPKN_NAMA",
    pegawai."INSTANSI_INDUK_NAMA",
    pegawai."INSTANSI_KERJA_NAMA",
    pegawai."SATUAN_KERJA_INDUK_NAMA",
    pegawai."SATUAN_KERJA_NAMA",
    pegawai."JABATAN_INSTANSI_ID",
    pegawai."BUP",
    date_part('year'::text, age((pegawai."TGL_LAHIR")::timestamp with time zone)) AS "AGE",
    vw."ESELON_1",
    vw."ESELON_2",
    vw."ESELON_3",
    vw."ESELON_4",
    date_part('year'::text, pegawai."TGL_LAHIR") AS tahun_lahir
   FROM (hris.pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)));


ALTER TABLE hris.daftar_pegawai OWNER TO postgres;

--
-- TOC entry 744 (class 1259 OID 1777593)
-- Name: daftar_pns_aktif; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.daftar_pns_aktif AS
 SELECT pegawai."ID",
    pegawai."PNS_ID",
    pegawai."NIP_LAMA",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."KEDUDUKAN_HUKUM_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."KARTU_PEGAWAI",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."GOL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."JENIS_JABATAN_IDx" AS "JENIS_JABATAN_ID",
    pegawai."JABATAN_ID",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."TAHUN_LULUS",
    pegawai."KPKN_ID",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."PHOTO",
    pegawai."TMT_PENSIUN",
    pegawai."LOKASI_KERJA",
    pegawai."NO_SURAT_DOKTER",
    pegawai."JML_ISTRI",
    pegawai."JML_ANAK",
    pegawai."TGL_SURAT_DOKTER",
    pegawai."NO_BEBAS_NARKOBA",
    pegawai."TGL_BEBAS_NARKOBA",
    pegawai."NO_CATATAN_POLISI",
    pegawai."TGL_CATATAN_POLISI",
    pegawai."AKTE_KELAHIRAN",
    pegawai."STATUS_HIDUP",
    pegawai."AKTE_MENINGGAL",
    pegawai."TGL_MENINGGAL",
    pegawai."NO_ASKES",
    pegawai."NO_TASPEN",
    pegawai."TGL_NPWP",
    pegawai."TEMPAT_LAHIR",
    pegawai."PENDIDIKAN",
    pegawai."TK_PENDIDIKAN",
    pegawai."TEMPAT_LAHIR_NAMA",
    pegawai."JENIS_JABATAN_NAMA",
    pegawai."JABATAN_NAMA",
    pegawai."KPKN_NAMA",
    pegawai."INSTANSI_INDUK_NAMA",
    pegawai."INSTANSI_KERJA_NAMA",
    pegawai."SATUAN_KERJA_INDUK_NAMA",
    pegawai."SATUAN_KERJA_NAMA",
    pegawai."JABATAN_INSTANSI_ID",
    pegawai."BUP",
    date_part('year'::text, age((pegawai."TGL_LAHIR")::timestamp with time zone)) AS "AGE"
   FROM hris.pegawai pegawai
  WHERE ((pegawai.status_pegawai = 1) AND ((pegawai.terminated_date IS NULL) OR ((pegawai.terminated_date IS NOT NULL) AND (pegawai.terminated_date > ('now'::text)::date))));


ALTER TABLE hris.daftar_pns_aktif OWNER TO postgres;

--
-- TOC entry 392 (class 1259 OID 35901)
-- Name: daftar_rohaniawan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.daftar_rohaniawan (
    id integer NOT NULL,
    nip character varying(30),
    nama character varying(100),
    jabatan character varying(100),
    agama integer,
    aktif character varying(5),
    pangkat_gol character varying(30)
);


ALTER TABLE hris.daftar_rohaniawan OWNER TO postgres;

--
-- TOC entry 393 (class 1259 OID 35904)
-- Name: daftar_rohaniawan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.daftar_rohaniawan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.daftar_rohaniawan_id_seq OWNER TO postgres;

--
-- TOC entry 6147 (class 0 OID 0)
-- Dependencies: 393
-- Name: daftar_rohaniawan_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.daftar_rohaniawan_id_seq OWNED BY hris.daftar_rohaniawan.id;


--
-- TOC entry 394 (class 1259 OID 35906)
-- Name: data_jabatan_tomi_04_04_2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.data_jabatan_tomi_04_04_2019 (
    "ID_Jabatan" character varying(255),
    "Nama_Jabatan" character varying(255),
    "Jenis_Jabatan" character varying(255),
    "Kelas_Jabatan" character varying(255),
    "Usia_Pensiun" character varying(255),
    "ID_JAB_BKN" character varying(255),
    "KATEGORI_JABATAN" character varying(255),
    "KELOMPOK_JFT" character varying(255),
    "JABATAN_SETARA" character varying(255),
    "TUNJANGAN_JFT" character varying(255),
    "AKTIF" character varying(255),
    "KETERANGAN" character varying(255)
);


ALTER TABLE hris.data_jabatan_tomi_04_04_2019 OWNER TO postgres;

--
-- TOC entry 395 (class 1259 OID 35912)
-- Name: data_jabatan_tomi_11_06_2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.data_jabatan_tomi_11_06_2019 (
    "ID_Jabatan" double precision NOT NULL,
    "Nama_Jabatan" text,
    "Jenis_Jabatan" character varying(100),
    "Kelas_Jabatan" character varying(50),
    "Usia_Pensiun" character varying(50),
    "ID_JAB_BKN" character varying(100),
    "KATEGORI_JABATAN" character varying(100),
    "KELOMPOK_JFT" character varying(100),
    "JABATAN_SETARA" text,
    "TUNJANGAN_JFT" character varying(50),
    "AKTIF" integer,
    "KETERANGAN" text,
    "PERPRES" text,
    "PERMENPAN" text
);


ALTER TABLE hris.data_jabatan_tomi_11_06_2019 OWNER TO postgres;

--
-- TOC entry 745 (class 1259 OID 1777599)
-- Name: data_pegawai; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.data_pegawai AS
 SELECT a."NIP_BARU",
    a."NAMA",
    a."JABATAN_NAMA",
    b."KELAS",
    c."NAMA" AS gol,
    c."NAMA_PANGKAT",
    d."NAMA_UNOR",
    a."STATUS_CPNS_PNS",
    e."NAMA" AS status,
    f."NAMA_UNOR" AS satker
   FROM (((((hris.pegawai a
     JOIN hris.jabatan b ON ((a."JABATAN_INSTANSI_ID" = (b."KODE_JABATAN")::bpchar)))
     JOIN hris.golongan c ON ((a."GOL_ID" = c."ID")))
     JOIN hris.unitkerja_1234 d ON (((a."UNOR_ID")::text = (d."ID")::text)))
     JOIN hris.unitkerja_1234 f ON (((f."ID")::text = (d."UNOR_INDUK")::text)))
     JOIN hris.kedudukan_hukum e ON (((a."KEDUDUKAN_HUKUM_ID")::text = (e."ID")::text)))
  WHERE (a."JABATAN_INSTANSI_ID" <> '0'::bpchar);


ALTER TABLE hris.data_pegawai OWNER TO postgres;

--
-- TOC entry 396 (class 1259 OID 35923)
-- Name: email_queue; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.email_queue (
    id smallint NOT NULL,
    to_email character varying(255),
    subject character varying(255),
    message text,
    alt_message text,
    max_attempts smallint,
    attempts smallint,
    success smallint,
    date_published date,
    last_attempt date,
    date_sent date,
    csv_attachment text
);


ALTER TABLE hris.email_queue OWNER TO postgres;

--
-- TOC entry 397 (class 1259 OID 35929)
-- Name: email_queue_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.email_queue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.email_queue_id_seq OWNER TO postgres;

--
-- TOC entry 6151 (class 0 OID 0)
-- Dependencies: 397
-- Name: email_queue_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.email_queue_id_seq OWNED BY hris.email_queue.id;


--
-- TOC entry 654 (class 1259 OID 265307)
-- Name: hari_libur; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.hari_libur (
    "ID" integer NOT NULL,
    "START_DATE" date,
    "END_DATE" date,
    "INFO" character varying(255)
);


ALTER TABLE hris.hari_libur OWNER TO postgres;

--
-- TOC entry 652 (class 1259 OID 265303)
-- Name: hari_libur_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."hari_libur_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."hari_libur_ID_seq" OWNER TO postgres;

--
-- TOC entry 6153 (class 0 OID 0)
-- Dependencies: 652
-- Name: hari_libur_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."hari_libur_ID_seq" OWNED BY hris.hari_libur."ID";


--
-- TOC entry 398 (class 1259 OID 35931)
-- Name: instansi; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.instansi (
    "ID" character varying(64) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.instansi OWNER TO postgres;

--
-- TOC entry 399 (class 1259 OID 35934)
-- Name: istri; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.istri (
    "ID" bigint NOT NULL,
    "PNS" smallint,
    "NAMA" character varying(255),
    "TANGGAL_MENIKAH" date,
    "AKTE_NIKAH" character varying(255),
    "TANGGAL_MENINGGAL" date,
    "AKTE_MENINGGAL" character varying(255),
    "TANGGAL_CERAI" date,
    "AKTE_CERAI" character varying(255),
    "KARSUS" character varying(255),
    "STATUS" smallint,
    "HUBUNGAN" smallint,
    "PNS_ID" character varying(32),
    "NIP" character varying(32)
);


ALTER TABLE hris.istri OWNER TO postgres;

--
-- TOC entry 6155 (class 0 OID 0)
-- Dependencies: 399
-- Name: COLUMN istri."PNS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.istri."PNS" IS '1=pns';


--
-- TOC entry 6156 (class 0 OID 0)
-- Dependencies: 399
-- Name: COLUMN istri."STATUS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.istri."STATUS" IS '1=menikah
2=cerai';


--
-- TOC entry 6157 (class 0 OID 0)
-- Dependencies: 399
-- Name: COLUMN istri."HUBUNGAN"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.istri."HUBUNGAN" IS '1=istri, 2= suami';


--
-- TOC entry 400 (class 1259 OID 35940)
-- Name: istri_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."istri_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."istri_ID_seq" OWNER TO postgres;

--
-- TOC entry 6159 (class 0 OID 0)
-- Dependencies: 400
-- Name: istri_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."istri_ID_seq" OWNED BY hris.istri."ID";


--
-- TOC entry 611 (class 1259 OID 47715)
-- Name: izin; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.izin (
    "ID" integer NOT NULL,
    "NIP_PNS" character varying(18) NOT NULL,
    "NAMA" character varying(100),
    "JABATAN" character varying(255),
    "UNIT_KERJA" character varying(255),
    "MASA_KERJA_TAHUN" integer,
    "MASA_KERJA_BULAN" integer,
    "GAJI_POKOK" character varying(10),
    "KODE_IZIN" smallint NOT NULL,
    "DARI_TANGGAL" date,
    "SAMPAI_TANGGAL" date,
    "TAHUN" character varying(4),
    "JUMLAH" integer,
    "SATUAN" character varying(10),
    "KETERANGAN" character varying(255),
    "ALAMAT_SELAMA_CUTI" character varying(255),
    "TLP_SELAMA_CUTI" character varying(20),
    "TGL_DIBUAT" date,
    "LAMPIRAN_FILE" character varying(50),
    "SISA_CUTI_TAHUN_N2" integer,
    "SISA_CUTI_TAHUN_N1" integer,
    "SISA_CUTI_TAHUN_N" integer,
    "ANAK_KE" character varying(1),
    "NIP_ATASAN" character varying(25),
    "STATUS_ATASAN" integer,
    "CATATAN_ATASAN" character varying(255),
    "NIP_PYBMC" character varying(25),
    "STATUS_PYBMC" integer,
    "CATATAN_PYBMC" character varying(255),
    "NAMA_ATASAN" character varying(100),
    "NAMA_PYBMC" character varying(100),
    "TGL_PERKIRAAN_LAHIR" date,
    "TGL_ATASAN" date,
    "TGL_PPK" date,
    "NAMA_UNIT_KERJA" character varying(150),
    "ALASAN_CUTI" character varying(255),
    "SELAMA_JAM" character varying(20),
    "SELAMA_MENIT" character varying(20),
    "STATUS_PENGAJUAN" smallint DEFAULT 1,
    "NO_SURAT" character varying(100),
    "TUJUAN_JAUH" smallint,
    "TAMBAHAN_HARI" smallint,
    "LUAR_NEGERI" smallint DEFAULT 0,
    "TEXT_BASE64_SIGN" text,
    "IS_SIGNED" smallint,
    "DRAFT_BASE64_SIGN" text,
    created_at date,
    updated_at date,
    "JAM" time without time zone,
    ref uuid,
    source smallint DEFAULT 1,
    status_kirim smallint
);


ALTER TABLE hris.izin OWNER TO postgres;

--
-- TOC entry 6160 (class 0 OID 0)
-- Dependencies: 611
-- Name: COLUMN izin."STATUS_PENGAJUAN"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin."STATUS_PENGAJUAN" IS '1=menunggu persetujuan';


--
-- TOC entry 6161 (class 0 OID 0)
-- Dependencies: 611
-- Name: COLUMN izin."LUAR_NEGERI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin."LUAR_NEGERI" IS '0 = dalam negeri/null, 1= luar negeri';


--
-- TOC entry 6162 (class 0 OID 0)
-- Dependencies: 611
-- Name: COLUMN izin."IS_SIGNED"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin."IS_SIGNED" IS '0';


--
-- TOC entry 6163 (class 0 OID 0)
-- Dependencies: 611
-- Name: COLUMN izin.source; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin.source IS '1=web,2=mobile';


--
-- TOC entry 6164 (class 0 OID 0)
-- Dependencies: 611
-- Name: COLUMN izin.status_kirim; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin.status_kirim IS '1=sudah pernah dikirim ke ekejadiran';


--
-- TOC entry 607 (class 1259 OID 47707)
-- Name: izin_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."izin_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."izin_ID_seq" OWNER TO postgres;

--
-- TOC entry 6166 (class 0 OID 0)
-- Dependencies: 607
-- Name: izin_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."izin_ID_seq" OWNED BY hris.izin."ID";


--
-- TOC entry 655 (class 1259 OID 265313)
-- Name: izin_alasan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.izin_alasan (
    "ID" smallint NOT NULL,
    "ALASAN" character varying(255),
    "JENIS_CUTI" smallint
);


ALTER TABLE hris.izin_alasan OWNER TO postgres;

--
-- TOC entry 653 (class 1259 OID 265305)
-- Name: izin_alasan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."izin_alasan_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."izin_alasan_ID_seq" OWNER TO postgres;

--
-- TOC entry 6168 (class 0 OID 0)
-- Dependencies: 653
-- Name: izin_alasan_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."izin_alasan_ID_seq" OWNED BY hris.izin_alasan."ID";


--
-- TOC entry 657 (class 1259 OID 282503)
-- Name: izin_verifikasi; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.izin_verifikasi (
    "ID" integer NOT NULL,
    "ID_PENGAJUAN" integer,
    "NIP_ATASAN" character varying(30),
    "STATUS_VERIFIKASI" smallint,
    "TANGGAL_VERIFIKASI" timestamp(6) without time zone,
    "ALASAN_DITOLAK" text
);


ALTER TABLE hris.izin_verifikasi OWNER TO postgres;

--
-- TOC entry 6169 (class 0 OID 0)
-- Dependencies: 657
-- Name: COLUMN izin_verifikasi."STATUS_VERIFIKASI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.izin_verifikasi."STATUS_VERIFIKASI" IS '''id''=>1,''value''=>''Menunggu Persetujuan''),
			array(''id''=>2,''value''=>''Proses''),
			array(''id''=>3,''value''=>''Disetujui''),
			array(''id''=>4,''value''=>''Perubahan''),
			array(''id''=>5,''value''=>''Ditangguhkan''),
			array(''id''=>6,''value''=>''Ditolak''';


--
-- TOC entry 656 (class 1259 OID 282501)
-- Name: izin_verifikasi_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."izin_verifikasi_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."izin_verifikasi_ID_seq" OWNER TO postgres;

--
-- TOC entry 6171 (class 0 OID 0)
-- Dependencies: 656
-- Name: izin_verifikasi_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."izin_verifikasi_ID_seq" OWNED BY hris.izin_verifikasi."ID";


--
-- TOC entry 680 (class 1259 OID 358442)
-- Name: jabatan_19_jan_2021; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jabatan_19_jan_2021 (
    "NO" integer,
    "KODE_JABATAN" character varying,
    "NAMA_JABATAN" character varying,
    "NAMA_JABATAN_FULL" text,
    "JENIS_JABATAN" character varying,
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(32),
    id bigint,
    "NAMA_JABATAN_BKN" character varying(255),
    "KATEGORI_JABATAN" character varying(100)
);


ALTER TABLE hris.jabatan_19_jan_2021 OWNER TO postgres;

--
-- TOC entry 691 (class 1259 OID 463000)
-- Name: jabatan_22_04_2021; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jabatan_22_04_2021 (
    "NO" integer,
    "KODE_JABATAN" character varying,
    "NAMA_JABATAN" character varying,
    "NAMA_JABATAN_FULL" text,
    "JENIS_JABATAN" character varying,
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(32),
    id bigint,
    "NAMA_JABATAN_BKN" character varying(255),
    "KATEGORI_JABATAN" character varying(100)
);


ALTER TABLE hris.jabatan_22_04_2021 OWNER TO postgres;

--
-- TOC entry 401 (class 1259 OID 35942)
-- Name: jabatan_backup_11_06_2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jabatan_backup_11_06_2019 (
    "NO" integer,
    "KODE_JABATAN" character varying,
    "NAMA_JABATAN" character varying,
    "NAMA_JABATAN_FULL" text,
    "JENIS_JABATAN" character varying,
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(32),
    id bigint,
    "NAMA_JABATAN_BKN" character varying(255)
);


ALTER TABLE hris.jabatan_backup_11_06_2019 OWNER TO postgres;

--
-- TOC entry 402 (class 1259 OID 35948)
-- Name: jabatan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jabatan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jabatan_id_seq OWNER TO postgres;

--
-- TOC entry 6174 (class 0 OID 0)
-- Dependencies: 402
-- Name: jabatan_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.jabatan_id_seq OWNED BY hris.jabatan.id;


--
-- TOC entry 403 (class 1259 OID 35950)
-- Name: jabatan_copy; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jabatan_copy (
    "NO" integer DEFAULT nextval('hris."jabatan_No_seq"'::regclass) NOT NULL,
    "KODE_JABATAN" character varying,
    "NAMA_JABATAN" character varying,
    "NAMA_JABATAN_FULL" text,
    "JENIS_JABATAN" character varying,
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(32),
    id bigint DEFAULT nextval('hris.jabatan_id_seq'::regclass) NOT NULL
);


ALTER TABLE hris.jabatan_copy OWNER TO postgres;

--
-- TOC entry 624 (class 1259 OID 213020)
-- Name: jenis_arsip; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_arsip (
    "ID" integer NOT NULL,
    "NAMA_JENIS" character varying(255),
    "KETERANGAN" text,
    "KATEGORI_ARSIP" smallint
);


ALTER TABLE hris.jenis_arsip OWNER TO postgres;

--
-- TOC entry 6176 (class 0 OID 0)
-- Dependencies: 624
-- Name: COLUMN jenis_arsip."NAMA_JENIS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.jenis_arsip."NAMA_JENIS" IS 'exa : ijazah SD, SK CPNS, SK PNS';


--
-- TOC entry 622 (class 1259 OID 213009)
-- Name: jenis_arsip_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jenis_arsip_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jenis_arsip_id_seq OWNER TO postgres;

--
-- TOC entry 6178 (class 0 OID 0)
-- Dependencies: 622
-- Name: jenis_arsip_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.jenis_arsip_id_seq OWNED BY hris.jenis_arsip."ID";


--
-- TOC entry 844 (class 1259 OID 1844208)
-- Name: jenis_diklat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_diklat (
    id integer NOT NULL,
    bkn_id integer,
    jenis_diklat character varying(50),
    kode character varying(2),
    status smallint DEFAULT 1
);


ALTER TABLE hris.jenis_diklat OWNER TO postgres;

--
-- TOC entry 841 (class 1259 OID 1844202)
-- Name: jenis_diklat_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jenis_diklat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jenis_diklat_id_seq OWNER TO postgres;

--
-- TOC entry 6179 (class 0 OID 0)
-- Dependencies: 841
-- Name: jenis_diklat_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.jenis_diklat_id_seq OWNED BY hris.jenis_diklat.id;


--
-- TOC entry 852 (class 1259 OID 2122423)
-- Name: jenis_diklat_siasn; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_diklat_siasn (
    id bigint NOT NULL,
    jenis_diklat character varying
);


ALTER TABLE hris.jenis_diklat_siasn OWNER TO postgres;

--
-- TOC entry 612 (class 1259 OID 47724)
-- Name: jenis_izin; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_izin (
    "ID" integer NOT NULL,
    "KODE" character varying(7) NOT NULL,
    "NAMA_IZIN" character varying(50) NOT NULL,
    "KETERANGAN" character varying(255),
    "PERSETUJUAN" character varying(100),
    "URUTAN" smallint,
    "STATUS" smallint DEFAULT 1,
    mobile smallint DEFAULT 0
);


ALTER TABLE hris.jenis_izin OWNER TO postgres;

--
-- TOC entry 608 (class 1259 OID 47709)
-- Name: jenis_izin_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."jenis_izin_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."jenis_izin_ID_seq" OWNER TO postgres;

--
-- TOC entry 6181 (class 0 OID 0)
-- Dependencies: 608
-- Name: jenis_izin_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."jenis_izin_ID_seq" OWNED BY hris.jenis_izin."ID";


--
-- TOC entry 404 (class 1259 OID 35958)
-- Name: jenis_kp; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_kp (
    "ID" character varying(64) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.jenis_kp OWNER TO postgres;

--
-- TOC entry 845 (class 1259 OID 1844215)
-- Name: jenis_kursus; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_kursus (
    id integer NOT NULL,
    bkn_id character varying(255),
    kode_cepat character varying(10),
    jenis character varying(50),
    nama character varying(255)
);


ALTER TABLE hris.jenis_kursus OWNER TO postgres;

--
-- TOC entry 842 (class 1259 OID 1844204)
-- Name: jenis_kursus_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jenis_kursus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jenis_kursus_id_seq OWNER TO postgres;

--
-- TOC entry 6183 (class 0 OID 0)
-- Dependencies: 842
-- Name: jenis_kursus_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.jenis_kursus_id_seq OWNED BY hris.jenis_kursus.id;


--
-- TOC entry 853 (class 1259 OID 2122432)
-- Name: jenis_rumpun_diklat_siasn; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.jenis_rumpun_diklat_siasn (
    id character varying NOT NULL,
    nama character varying,
    urusan character varying,
    pelayanan_dasar boolean,
    peraturan_id character varying,
    keterangan character varying
);


ALTER TABLE hris.jenis_rumpun_diklat_siasn OWNER TO postgres;

--
-- TOC entry 696 (class 1259 OID 522560)
-- Name: mst_jenis_satker; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.mst_jenis_satker (
    id_jenis smallint NOT NULL,
    nama_jenis_satker character varying(50)
);


ALTER TABLE hris.mst_jenis_satker OWNER TO postgres;

--
-- TOC entry 692 (class 1259 OID 522552)
-- Name: jenis_satker_id_jenis_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.jenis_satker_id_jenis_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.jenis_satker_id_jenis_seq OWNER TO postgres;

--
-- TOC entry 6184 (class 0 OID 0)
-- Dependencies: 692
-- Name: jenis_satker_id_jenis_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.jenis_satker_id_jenis_seq OWNED BY hris.mst_jenis_satker.id_jenis;


--
-- TOC entry 405 (class 1259 OID 35961)
-- Name: kandidat_baperjakat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kandidat_baperjakat (
    "ID" smallint NOT NULL,
    "NIP" character varying(32),
    "URUTAN_DEFAULT" smallint,
    "URUTAN_UPDATE" smallint,
    "TAHUN" character varying(4),
    "STATUS" smallint,
    "UNOR_ID" character varying(32),
    "STATUS_TAMBAHAN" smallint,
    "NILAI_ASSESMENT" real,
    "PANGGOL" character varying(255),
    "PENDIDIKAN" character varying(255),
    "HUKDIS" character varying(255),
    "UPDATE_BY" smallint,
    "UPDATE_DATE" date,
    "ID_PERIODE" integer,
    "JABATAN_ID" character varying(4),
    "NAMA_JABATAN" character varying(255),
    "TGL_PELANTIKAN" date,
    "NO_SK_PELANTIKAN" character varying(50),
    "KATEGORI" smallint,
    "STATUS_MENTERI" smallint
);


ALTER TABLE hris.kandidat_baperjakat OWNER TO postgres;

--
-- TOC entry 6185 (class 0 OID 0)
-- Dependencies: 405
-- Name: COLUMN kandidat_baperjakat."STATUS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.kandidat_baperjakat."STATUS" IS '1=diterima,0 tidak diterima';


--
-- TOC entry 6186 (class 0 OID 0)
-- Dependencies: 405
-- Name: COLUMN kandidat_baperjakat."STATUS_TAMBAHAN"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.kandidat_baperjakat."STATUS_TAMBAHAN" IS '1=admin, 2= sistem';


--
-- TOC entry 6187 (class 0 OID 0)
-- Dependencies: 405
-- Name: COLUMN kandidat_baperjakat."KATEGORI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.kandidat_baperjakat."KATEGORI" IS '1=rotasi,2=promosi';


--
-- TOC entry 406 (class 1259 OID 35967)
-- Name: kandidat_baperjakat_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."kandidat_baperjakat_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."kandidat_baperjakat_ID_seq" OWNER TO postgres;

--
-- TOC entry 6189 (class 0 OID 0)
-- Dependencies: 406
-- Name: kandidat_baperjakat_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."kandidat_baperjakat_ID_seq" OWNED BY hris.kandidat_baperjakat."ID";


--
-- TOC entry 407 (class 1259 OID 35969)
-- Name: kategori_ds; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kategori_ds (
    id smallint NOT NULL,
    kategori_ds character varying(100)
);


ALTER TABLE hris.kategori_ds OWNER TO postgres;

--
-- TOC entry 408 (class 1259 OID 35972)
-- Name: kategori_ds_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.kategori_ds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.kategori_ds_id_seq OWNER TO postgres;

--
-- TOC entry 6191 (class 0 OID 0)
-- Dependencies: 408
-- Name: kategori_ds_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.kategori_ds_id_seq OWNED BY hris.kategori_ds.id;


--
-- TOC entry 636 (class 1259 OID 249372)
-- Name: kategori_jenis_arsip; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kategori_jenis_arsip (
    "ID" smallint NOT NULL,
    "KATEGORI" character varying(255)
);


ALTER TABLE hris.kategori_jenis_arsip OWNER TO postgres;

--
-- TOC entry 628 (class 1259 OID 249356)
-- Name: kategori_jenis_arsip_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."kategori_jenis_arsip_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."kategori_jenis_arsip_ID_seq" OWNER TO postgres;

--
-- TOC entry 6193 (class 0 OID 0)
-- Dependencies: 628
-- Name: kategori_jenis_arsip_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."kategori_jenis_arsip_ID_seq" OWNED BY hris.kategori_jenis_arsip."ID";


--
-- TOC entry 409 (class 1259 OID 35974)
-- Name: kpkn; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kpkn (
    "ID" character varying(255) NOT NULL,
    "NAMA" character varying(255)
);


ALTER TABLE hris.kpkn OWNER TO postgres;

--
-- TOC entry 410 (class 1259 OID 35980)
-- Name: kuota_jabatan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.kuota_jabatan_id_seq
    START WITH 6104
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.kuota_jabatan_id_seq OWNER TO postgres;

--
-- TOC entry 411 (class 1259 OID 35982)
-- Name: kuota_jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kuota_jabatan (
    "ID" bigint DEFAULT nextval('hris.kuota_jabatan_id_seq'::regclass) NOT NULL,
    "KODE_UNIT_KERJA" character varying(50),
    "ID_JABATAN" character varying(32),
    "JUMLAH_PEMANGKU_JABATAN" smallint,
    "KETERANGAN" character varying(255),
    "FORMASI" character varying(50),
    "SKALA_PRIORITAS" character varying(50),
    "ID_JABATAN_PENYETARAAN" character varying(50),
    "PERATURAN" character varying(50),
    "KD_INTERNAL" character varying(50),
    kepmen_peta_jabatan character varying(100),
    nomor_kepmen_peta_jabatan character varying(100),
    tentang_kepmen_peta_jabatan character varying(200),
    aktif smallint
);


ALTER TABLE hris.kuota_jabatan OWNER TO postgres;

--
-- TOC entry 412 (class 1259 OID 35986)
-- Name: kuota_jabatan_1209; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kuota_jabatan_1209 (
    "ID" integer DEFAULT nextval('hris.kuota_jabatan_id_seq'::regclass) NOT NULL,
    "KODE_UNIT_KERJA" character varying(255),
    "ID_JABATAN" character varying(32),
    "JUMLAH_PEMANGKU_JABATAN" integer
);


ALTER TABLE hris.kuota_jabatan_1209 OWNER TO postgres;

--
-- TOC entry 413 (class 1259 OID 35990)
-- Name: kuota_jabatan_16sep2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.kuota_jabatan_16sep2019 (
    "ID" integer DEFAULT nextval('hris.kuota_jabatan_id_seq'::regclass) NOT NULL,
    "KODE_UNIT_KERJA" character varying(255),
    "ID_JABATAN" character varying(32),
    "JUMLAH_PEMANGKU_JABATAN" integer
);


ALTER TABLE hris.kuota_jabatan_16sep2019 OWNER TO postgres;

--
-- TOC entry 414 (class 1259 OID 35994)
-- Name: layanan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.layanan (
    id bigint NOT NULL,
    layanan_tipe_id bigint,
    name character varying(255),
    keterangan character varying(255),
    expired_date date,
    _created_at timestamp(6) without time zone DEFAULT now() NOT NULL,
    active boolean
);


ALTER TABLE hris.layanan OWNER TO postgres;

--
-- TOC entry 415 (class 1259 OID 36001)
-- Name: layanan_tipe; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.layanan_tipe (
    id bigint NOT NULL,
    name character varying(255),
    description character varying(255),
    active boolean
);


ALTER TABLE hris.layanan_tipe OWNER TO postgres;

--
-- TOC entry 416 (class 1259 OID 36007)
-- Name: layanan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.layanan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.layanan_id_seq OWNER TO postgres;

--
-- TOC entry 6200 (class 0 OID 0)
-- Dependencies: 416
-- Name: layanan_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.layanan_id_seq OWNED BY hris.layanan_tipe.id;


--
-- TOC entry 417 (class 1259 OID 36009)
-- Name: layanan_id_seq1; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.layanan_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.layanan_id_seq1 OWNER TO postgres;

--
-- TOC entry 6201 (class 0 OID 0)
-- Dependencies: 417
-- Name: layanan_id_seq1; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.layanan_id_seq1 OWNED BY hris.layanan.id;


--
-- TOC entry 418 (class 1259 OID 36011)
-- Name: layanan_usulan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.layanan_usulan (
    id bigint NOT NULL,
    layanan_id bigint,
    pegawai_id bigint,
    created_at timestamp(6) without time zone,
    created_by bigint,
    status bigint,
    nip character varying(255),
    nama character varying(255),
    jabatan character varying(255),
    golongan_ruang character varying(255),
    unit_kerja character varying(255),
    satuan_kerja character varying(255),
    "F1" character varying(255),
    "F2" character varying(255),
    "F3" character varying(255),
    "F4" character varying(255),
    "F5" character varying(255),
    "F6" character varying(255),
    "F7" character varying(255),
    "F8" character varying(255),
    "F9" character varying(255),
    "F10" character varying(255),
    "F11" character varying(255),
    "F12" character varying(255),
    "F13" character varying(255),
    "F14" character varying(255),
    "F15" character varying(255),
    no_surat_pengantar character varying(255),
    file_surat_pengantar character varying(255)
);


ALTER TABLE hris.layanan_usulan OWNER TO postgres;

--
-- TOC entry 419 (class 1259 OID 36017)
-- Name: layanan_usulan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.layanan_usulan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.layanan_usulan_id_seq OWNER TO postgres;

--
-- TOC entry 6203 (class 0 OID 0)
-- Dependencies: 419
-- Name: layanan_usulan_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.layanan_usulan_id_seq OWNED BY hris.layanan_usulan.id;


--
-- TOC entry 651 (class 1259 OID 264128)
-- Name: line_approval_izin; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.line_approval_izin (
    "ID" integer NOT NULL,
    "PNS_NIP" character varying(30),
    "NIP_ATASAN" character varying(30),
    "JENIS" smallint,
    "KETERANGAN_TAMBAHAN" character varying(200),
    "NAMA_ATASAN" character varying(100),
    "SEBAGAI" smallint
);


ALTER TABLE hris.line_approval_izin OWNER TO postgres;

--
-- TOC entry 6204 (class 0 OID 0)
-- Dependencies: 651
-- Name: COLUMN line_approval_izin."JENIS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.line_approval_izin."JENIS" IS '1=ATASAN LANGSUNG, 2 = PPK';


--
-- TOC entry 6205 (class 0 OID 0)
-- Dependencies: 651
-- Name: COLUMN line_approval_izin."SEBAGAI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.line_approval_izin."SEBAGAI" IS '1,2,3,4';


--
-- TOC entry 649 (class 1259 OID 264119)
-- Name: line_approval_izin_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."line_approval_izin_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."line_approval_izin_ID_seq" OWNER TO postgres;

--
-- TOC entry 6207 (class 0 OID 0)
-- Dependencies: 649
-- Name: line_approval_izin_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."line_approval_izin_ID_seq" OWNED BY hris.line_approval_izin."ID";


--
-- TOC entry 420 (class 1259 OID 36019)
-- Name: log_ds; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.log_ds (
    "ID" integer NOT NULL,
    "ID_FILE" character varying(32),
    "NIK" character varying(30),
    "KETERANGAN" character varying(255),
    "CREATED_DATE" timestamp(0) without time zone,
    "CREATED_BY" integer,
    "STATUS" smallint,
    "PROSES_CRON" smallint DEFAULT 0
);


ALTER TABLE hris.log_ds OWNER TO postgres;

--
-- TOC entry 6208 (class 0 OID 0)
-- Dependencies: 420
-- Name: COLUMN log_ds."STATUS"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.log_ds."STATUS" IS '1:gagal, 2:berhasil';


--
-- TOC entry 6209 (class 0 OID 0)
-- Dependencies: 420
-- Name: COLUMN log_ds."PROSES_CRON"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.log_ds."PROSES_CRON" IS '0 = belum, 1 = sudah';


--
-- TOC entry 421 (class 1259 OID 36022)
-- Name: log_ds_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."log_ds_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."log_ds_ID_seq" OWNER TO postgres;

--
-- TOC entry 6211 (class 0 OID 0)
-- Dependencies: 421
-- Name: log_ds_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."log_ds_ID_seq" OWNED BY hris.log_ds."ID";


--
-- TOC entry 846 (class 1259 OID 1844224)
-- Name: log_request; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.log_request (
    id integer NOT NULL,
    url text NOT NULL,
    method character varying(10),
    params text,
    response_code integer,
    response text,
    created_at timestamp(6) without time zone DEFAULT now()
);


ALTER TABLE hris.log_request OWNER TO postgres;

--
-- TOC entry 843 (class 1259 OID 1844206)
-- Name: log_request_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.log_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.log_request_id_seq OWNER TO postgres;

--
-- TOC entry 6212 (class 0 OID 0)
-- Dependencies: 843
-- Name: log_request_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.log_request_id_seq OWNED BY hris.log_request.id;


--
-- TOC entry 422 (class 1259 OID 36024)
-- Name: log_transaksi; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.log_transaksi (
    "ID" integer NOT NULL,
    "NIP" character varying(30),
    "NAMA_KOMPUTER" character varying(30),
    "USER" character varying(30),
    "TGL_MODIFIKASI" date,
    "JAM_MODIFIKASI" time(6) without time zone,
    "YANG_DIUBAH" character varying(255),
    "MODULE" character varying(30)
);


ALTER TABLE hris.log_transaksi OWNER TO postgres;

--
-- TOC entry 423 (class 1259 OID 36027)
-- Name: log_transaksi_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."log_transaksi_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."log_transaksi_ID_seq" OWNER TO postgres;

--
-- TOC entry 6214 (class 0 OID 0)
-- Dependencies: 423
-- Name: log_transaksi_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."log_transaksi_ID_seq" OWNED BY hris.log_transaksi."ID";


--
-- TOC entry 424 (class 1259 OID 36029)
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.login_attempts_id_seq
    START WITH 9073
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.login_attempts_id_seq OWNER TO postgres;

--
-- TOC entry 425 (class 1259 OID 36031)
-- Name: login_attempts; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.login_attempts (
    ip_address character(40) NOT NULL,
    login character(50) NOT NULL,
    "time" time(6) without time zone,
    id integer DEFAULT nextval('hris.login_attempts_id_seq'::regclass) NOT NULL
);


ALTER TABLE hris.login_attempts OWNER TO postgres;

--
-- TOC entry 720 (class 1259 OID 994550)
-- Name: mapping_unor_induk; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.mapping_unor_induk AS
 SELECT t."ID",
    t."NAMA_UNOR",
        CASE
            WHEN ((t."ESELON_2" IS NULL) OR (btrim((t."ESELON_2")::text) = ''::text)) THEN t."ESELON_1"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'universitas_dikti'::text) THEN t."ESELON_2"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Multimedia Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Televisi Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Radio Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            ELSE t."ESELON_2"
        END AS unor_induk,
        CASE
            WHEN ((t."ESELON_2" IS NULL) OR (btrim((t."ESELON_2")::text) = ''::text)) THEN t."NAMA_UNOR_ESELON_1"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'universitas_dikti'::text) THEN t."NAMA_UNOR_ESELON_2"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Multimedia Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Televisi Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Radio Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            ELSE t."NAMA_UNOR_ESELON_2"
        END AS nama_unor_induk
   FROM hris.vw_unit_list t;


ALTER TABLE hris.mapping_unor_induk OWNER TO postgres;

--
-- TOC entry 697 (class 1259 OID 522566)
-- Name: mst_peraturan_otk; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.mst_peraturan_otk (
    id_peraturan smallint NOT NULL,
    no_peraturan character varying(100)
);


ALTER TABLE hris.mst_peraturan_otk OWNER TO postgres;

--
-- TOC entry 426 (class 1259 OID 36035)
-- Name: mst_templates; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.mst_templates (
    id integer NOT NULL,
    name character varying(255),
    template_file character varying(255)
);


ALTER TABLE hris.mst_templates OWNER TO postgres;

--
-- TOC entry 427 (class 1259 OID 36041)
-- Name: mst_templates_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.mst_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.mst_templates_id_seq OWNER TO postgres;

--
-- TOC entry 6217 (class 0 OID 0)
-- Dependencies: 427
-- Name: mst_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.mst_templates_id_seq OWNED BY hris.mst_templates.id;


--
-- TOC entry 685 (class 1259 OID 437035)
-- Name: pns_aktif; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.pns_aktif AS
 SELECT temp."ID",
        CASE
            WHEN ((temp.masa_kerja[1] + temp.bulan_swasta) >= 12) THEN ((temp.masa_kerja[0] + temp.tahun_swasta) + 1)
            ELSE (temp.masa_kerja[0] + temp.tahun_swasta)
        END AS masa_kerja_th,
        CASE
            WHEN ((temp.masa_kerja[1] + temp.bulan_swasta) >= 12) THEN ((temp.masa_kerja[1] + temp.bulan_swasta) - 12)
            ELSE (temp.masa_kerja[1] + temp.bulan_swasta)
        END AS masa_kerja_bl
   FROM ( SELECT pegawai."ID",
            hris.get_masa_kerja_arr(pegawai."TMT_CPNS", ('now'::text)::date) AS masa_kerja,
            pegawai."MK_TAHUN_SWASTA" AS tahun_swasta,
            pegawai."MK_BULAN_SWASTA" AS bulan_swasta
           FROM hris.pegawai
          WHERE ((pegawai.status_pegawai = 1) AND ((pegawai.terminated_date IS NULL) OR ((pegawai.terminated_date IS NOT NULL) AND (pegawai.terminated_date > ('now'::text)::date))))) temp;


ALTER TABLE hris.pns_aktif OWNER TO postgres;

--
-- TOC entry 769 (class 1259 OID 1777756)
-- Name: mv_duk; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_duk AS
 SELECT vw."NAMA_UNOR",
    pegawai."JENIS_JABATAN_ID",
    pegawai."JABATAN_ID",
    jabatan."NAMA_JABATAN",
    pegawai."NIP_LAMA",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    vw."ESELON_ID" AS vw_eselon_id,
    pegawai."GOL_ID",
    (((golongan."NAMA_PANGKAT")::text || ' '::text) || (golongan."NAMA")::text) AS golongan_text,
    'jabatanku'::text AS jabatan_text,
    pegawai."PNS_ID",
    (((date_part('year'::text, (now())::date) - date_part('year'::text, pegawai."TGL_LAHIR")) * (12)::double precision) + (date_part('month'::text, (now())::date) - date_part('month'::text, pegawai."TGL_LAHIR"))) AS bulan_usia,
    '#'::text AS separator,
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."KEDUDUKAN_HUKUM_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."KARTU_PEGAWAI",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."PENDIDIKAN",
    pegawai."TAHUN_LULUS",
    pegawai."KPKN_ID",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."ID",
    pegawai."PHOTO",
    pegawai."TMT_PENSIUN",
    pegawai."BUP",
    vw."ESELON_1",
    vw."ESELON_2",
    vw."ESELON_3",
    vw."ESELON_4",
    vw."NAMA_UNOR_ESELON_4",
    vw."NAMA_UNOR_ESELON_3",
    vw."NAMA_UNOR_ESELON_2",
    vw."NAMA_UNOR_ESELON_1"
   FROM ((((hris.pns_aktif pa
     LEFT JOIN hris.pegawai pegawai ON ((pa."ID" = pegawai."ID")))
     LEFT JOIN hris.golongan ON (((pegawai."GOL_ID")::text = (golongan."ID")::text)))
     LEFT JOIN hris.vw_unit_list vw ON (((vw."ID")::text = (pegawai."UNOR_ID")::text)))
     LEFT JOIN hris.jabatan jabatan ON ((pegawai."JABATAN_INSTANSI_ID" = (jabatan."KODE_JABATAN")::bpchar)))
  WHERE ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> ALL (ARRAY[('14'::character varying)::text, ('52'::character varying)::text, ('66'::character varying)::text, ('67'::character varying)::text, ('77'::character varying)::text, ('78'::character varying)::text, ('98'::character varying)::text, ('99'::character varying)::text]))
  ORDER BY pegawai."JENIS_JABATAN_ID", vw."ESELON_ID", vw."ESELON_1", vw."ESELON_2", vw."ESELON_3", vw."ESELON_4", pegawai."JABATAN_ID", vw."NAMA_UNOR_FULL", pegawai."GOL_ID" DESC, pegawai."TMT_GOLONGAN", pegawai."TMT_JABATAN", pegawai."TMT_CPNS", pegawai."TGL_LAHIR"
  WITH NO DATA;


ALTER TABLE hris.mv_duk OWNER TO postgres;

--
-- TOC entry 770 (class 1259 OID 1777771)
-- Name: mv_jml_unor_induk; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_jml_unor_induk AS
 SELECT pegawai."UNOR_INDUK_ID",
    uk."JENIS_SATKER",
    uk."NAMA_UNOR",
    u."NAMA_UNOR" AS nama_unor_atas,
    count(pegawai."ID") AS jumlah
   FROM (((hris.pegawai pegawai
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
     LEFT JOIN hris.unitkerja uk ON (((uk."ID")::text = (pegawai."UNOR_INDUK_ID")::text)))
     LEFT JOIN hris.unitkerja u ON (((u."ID")::text = (uk."DIATASAN_ID")::text)))
  WHERE (((pegawai."KEDUDUKAN_HUKUM_ID")::text <> ALL (ARRAY[('14'::character varying)::text, ('52'::character varying)::text, ('66'::character varying)::text, ('67'::character varying)::text, ('77'::character varying)::text, ('88'::character varying)::text, ('98'::character varying)::text, ('99'::character varying)::text, ('100'::character varying)::text])) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)) AND (pa."ID" IS NOT NULL))
  GROUP BY pegawai."UNOR_INDUK_ID", uk."JENIS_SATKER", uk."NAMA_UNOR", u."NAMA_UNOR"
  ORDER BY u."NAMA_UNOR", uk."NAMA_UNOR"
  WITH NO DATA;


ALTER TABLE hris.mv_jml_unor_induk OWNER TO postgres;

--
-- TOC entry 658 (class 1259 OID 301927)
-- Name: mv_kategori_ds; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_kategori_ds AS
 SELECT DISTINCT tbl_file_ds.kategori AS kategori_ds
   FROM hris.tbl_file_ds
  ORDER BY tbl_file_ds.kategori
  WITH NO DATA;


ALTER TABLE hris.mv_kategori_ds OWNER TO postgres;

--
-- TOC entry 766 (class 1259 OID 1777729)
-- Name: mv_nominatif_pegawai; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_nominatif_pegawai AS
 SELECT pegawai."ID",
    btrim((pegawai."PNS_ID")::text) AS "PNS_ID",
    btrim((pegawai."GELAR_DEPAN")::text) AS "GELAR_DEPAN",
    btrim((pegawai."GELAR_BELAKANG")::text) AS "GELAR_BELAKANG",
    btrim((pegawai."NAMA")::text) AS "NAMA",
    btrim((pegawai."NIP_LAMA")::text) AS "NIP_LAMA",
    btrim((pegawai."NIP_BARU")::text) AS "NIP_BARU",
    btrim((pegawai."JENIS_KELAMIN")::text) AS "JENIS_KELAMIN",
    btrim((pegawai."TEMPAT_LAHIR_ID")::text) AS "TEMPAT_LAHIR_ID",
    pegawai."TGL_SK_CPNS",
    pegawai."TGL_LAHIR",
    date((pegawai."TGL_LAHIR" + ('1 year'::interval * (jabatan."PENSIUN")::double precision))) AS estimasi_pensiun,
    date_part('year'::text, age((pegawai."TGL_LAHIR")::timestamp with time zone)) AS age,
    pegawai."TMT_PENSIUN",
    btrim((pegawai."PENDIDIKAN_ID")::text) AS "PENDIDIKAN_ID",
    btrim((pendidikan."NAMA")::text) AS "NAMA_PENDIDIKAN",
    btrim((agama."NAMA")::text) AS "NAMA_AGAMA",
    btrim((jenis_jabatan."NAMA")::text) AS "JENIS_JABATAN",
    jabatan."KELAS",
    btrim((jabatan."NAMA_JABATAN")::text) AS "NAMA_JABATAN",
    btrim((golongan."NAMA")::text) AS "NAMA_GOLONGAN",
    btrim((golongan."NAMA_PANGKAT")::text) AS "NAMA_PANGKAT",
    pegawai."GOL_ID",
    pegawai."TMT_GOLONGAN",
    btrim((vw."NAMA_UNOR_ESELON_4")::text) AS "NAMA_UNOR_ESELON_4",
    btrim((vw."NAMA_UNOR_ESELON_3")::text) AS "NAMA_UNOR_ESELON_3",
    btrim((vw."NAMA_UNOR_ESELON_2")::text) AS "NAMA_UNOR_ESELON_2",
    btrim((vw."NAMA_UNOR_ESELON_1")::text) AS "NAMA_UNOR_ESELON_1",
    btrim((vw."ID")::text) AS "ID_UNOR",
    btrim((vw."ESELON_1")::text) AS "ESELON_1",
    btrim((vw."ESELON_2")::text) AS "ESELON_2",
    btrim((vw."ESELON_3")::text) AS "ESELON_3",
    btrim((vw."ESELON_4")::text) AS "ESELON_4",
    btrim((vw."ESELON_ID")::text) AS "ESELON_ID",
    vw."JENIS_SATKER",
    pegawai."TK_PENDIDIKAN",
    jabatan."KATEGORI_JABATAN",
    btrim((kedudukan_hukum."NAMA")::text) AS "KEDUDUKAN_HUKUM_NAMA",
    btrim((pegawai."NOMOR_SK_CPNS")::text) AS "NOMOR_SK_CPNS",
    pegawai."TMT_CPNS",
    btrim((uk."NAMA_UNOR")::text) AS nama_satker,
    btrim((pegawai."NIK")::text) AS "NIK",
    btrim((pegawai."NOMOR_HP")::text) AS "NOMOR_HP",
    btrim((pegawai."NOMOR_DARURAT")::text) AS "NOMOR_DARURAT",
    btrim((pegawai."EMAIL")::text) AS "EMAIL",
    btrim((pegawai."EMAIL_DIKBUD")::text) AS "EMAIL_DIKBUD",
    vw."NAMA_UNOR_FULL",
    lokasi."NAMA" AS "TEMPAT_LAHIR_NAMA"
   FROM ((((((((((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
     LEFT JOIN hris.unitkerja uk ON (((uk."ID")::text = (vw."UNOR_INDUK")::text)))
     LEFT JOIN hris.golongan ON ((pegawai."GOL_ID" = golongan."ID")))
     LEFT JOIN hris.lokasi ON (((lokasi."ID")::text = (pegawai."TEMPAT_LAHIR_ID")::text)))
     LEFT JOIN hris.pendidikan ON (((pendidikan."ID")::text = (pegawai."PENDIDIKAN_ID")::text)))
     LEFT JOIN hris.agama ON ((agama."ID" = pegawai."AGAMA_ID")))
     LEFT JOIN hris.kedudukan_hukum ON (((kedudukan_hukum."ID")::text = (pegawai."KEDUDUKAN_HUKUM_ID")::text)))
     LEFT JOIN hris.jabatan ON ((pegawai."JABATAN_INSTANSI_REAL_ID" = (jabatan."KODE_JABATAN")::bpchar)))
     LEFT JOIN hris.jenis_jabatan ON (((jenis_jabatan."ID")::text = (jabatan."JENIS_JABATAN")::text)))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> ALL (ARRAY[('14'::character varying)::text, ('52'::character varying)::text, ('66'::character varying)::text, ('67'::character varying)::text, ('77'::character varying)::text, ('78'::character varying)::text, ('98'::character varying)::text, ('99'::character varying)::text])) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)))
  ORDER BY (btrim((pegawai."NAMA")::text))
  WITH NO DATA;


ALTER TABLE hris.mv_nominatif_pegawai OWNER TO postgres;

--
-- TOC entry 765 (class 1259 OID 1777721)
-- Name: mv_pegawai; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_pegawai AS
 SELECT btrim((pegawai."ID")::text) AS "ID",
    btrim((pegawai."PNS_ID")::text) AS "PNS_ID",
    btrim((pegawai."NIP_LAMA")::text) AS "NIP_LAMA",
    btrim((pegawai."NIP_BARU")::text) AS "NIP_BARU",
    btrim((pegawai."NAMA")::text) AS "NAMA",
    btrim((pegawai."GELAR_DEPAN")::text) AS "GELAR_DEPAN",
    btrim((pegawai."GELAR_BELAKANG")::text) AS "GELAR_BELAKANG",
    btrim((pegawai."TEMPAT_LAHIR_ID")::text) AS "TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    btrim((pegawai."JENIS_KELAMIN")::text) AS "JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    btrim((pegawai."JENIS_KAWIN_ID")::text) AS "JENIS_KAWIN_ID",
    btrim((pegawai."NIK")::text) AS "NIK",
    btrim((pegawai."NOMOR_DARURAT")::text) AS "NOMOR_DARURAT",
    btrim((pegawai."NOMOR_HP")::text) AS "NOMOR_HP",
    btrim((pegawai."EMAIL")::text) AS "EMAIL",
    btrim((pegawai."ALAMAT")::text) AS "ALAMAT",
    btrim((pegawai."NPWP")::text) AS "NPWP",
    btrim((pegawai."BPJS")::text) AS "BPJS",
    btrim((pegawai."JENIS_PEGAWAI_ID")::text) AS "JENIS_PEGAWAI_ID",
    btrim((pegawai."KEDUDUKAN_HUKUM_ID")::text) AS "KEDUDUKAN_HUKUM_ID",
    btrim((pegawai."STATUS_CPNS_PNS")::text) AS "STATUS_CPNS_PNS",
    btrim((pegawai."KARTU_PEGAWAI")::text) AS "KARTU_PEGAWAI",
    btrim((pegawai."NOMOR_SK_CPNS")::text) AS "NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    btrim((pegawai."GOL_AWAL_ID")::text) AS "GOL_AWAL_ID",
    pegawai."GOL_ID",
    pegawai."TMT_GOLONGAN",
    btrim((pegawai."MK_TAHUN")::text) AS "MK_TAHUN",
    btrim((pegawai."MK_BULAN")::text) AS "MK_BULAN",
    btrim((pegawai."JENIS_JABATAN_IDx")::text) AS "JENIS_JABATAN_IDx",
    btrim((pegawai."JABATAN_ID")::text) AS "JABATAN_ID",
    pegawai."TMT_JABATAN",
    btrim((pegawai."PENDIDIKAN_ID")::text) AS "PENDIDIKAN_ID",
    btrim((pendidikan."NAMA")::text) AS "NAMA_PENDIDIKAN",
    btrim((tkpendidikan."NAMA")::text) AS "TINGKAT_PENDIDIKAN_NAMA",
    btrim((pegawai."TAHUN_LULUS")::text) AS "TAHUN_LULUS",
    btrim((pegawai."KPKN_ID")::text) AS "KPKN_ID",
    btrim((pegawai."LOKASI_KERJA_ID")::text) AS "LOKASI_KERJA_ID",
    btrim((pegawai."UNOR_ID")::text) AS "UNOR_ID",
    btrim((pegawai."UNOR_INDUK_ID")::text) AS "UNOR_INDUK_ID",
    btrim((pegawai."INSTANSI_INDUK_ID")::text) AS "INSTANSI_INDUK_ID",
    btrim((pegawai."INSTANSI_KERJA_ID")::text) AS "INSTANSI_KERJA_ID",
    btrim((pegawai."SATUAN_KERJA_INDUK_ID")::text) AS "SATUAN_KERJA_INDUK_ID",
    btrim((pegawai."SATUAN_KERJA_KERJA_ID")::text) AS "SATUAN_KERJA_KERJA_ID",
    btrim((pegawai."GOLONGAN_DARAH")::text) AS "GOLONGAN_DARAH",
    btrim((pegawai."PHOTO")::text) AS "PHOTO",
    pegawai."TMT_PENSIUN",
    btrim((pegawai."LOKASI_KERJA")::text) AS "LOKASI_KERJA",
    btrim((pegawai."JML_ISTRI")::text) AS "JML_ISTRI",
    btrim((pegawai."JML_ANAK")::text) AS "JML_ANAK",
    btrim((pegawai."NO_SURAT_DOKTER")::text) AS "NO_SURAT_DOKTER",
    pegawai."TGL_SURAT_DOKTER",
    btrim((pegawai."NO_BEBAS_NARKOBA")::text) AS "NO_BEBAS_NARKOBA",
    pegawai."TGL_BEBAS_NARKOBA",
    btrim((pegawai."NO_CATATAN_POLISI")::text) AS "NO_CATATAN_POLISI",
    pegawai."TGL_CATATAN_POLISI",
    btrim((pegawai."AKTE_KELAHIRAN")::text) AS "AKTE_KELAHIRAN",
    btrim((pegawai."STATUS_HIDUP")::text) AS "STATUS_HIDUP",
    btrim((pegawai."AKTE_MENINGGAL")::text) AS "AKTE_MENINGGAL",
    pegawai."TGL_MENINGGAL",
    btrim((pegawai."NO_ASKES")::text) AS "NO_ASKES",
    btrim((pegawai."NO_TASPEN")::text) AS "NO_TASPEN",
    pegawai."TGL_NPWP",
    btrim((pegawai."TEMPAT_LAHIR")::text) AS "TEMPAT_LAHIR",
    btrim((pegawai."PENDIDIKAN")::text) AS "PENDIDIKAN",
    btrim((pegawai."TK_PENDIDIKAN")::text) AS "TK_PENDIDIKAN",
    btrim((pegawai."TEMPAT_LAHIR_NAMA")::text) AS "TEMPAT_LAHIR_NAMA",
    btrim((pegawai."JENIS_JABATAN_NAMA")::text) AS "JENIS_JABATAN_NAMA",
    btrim((pegawai."JABATAN_NAMA")::text) AS "JABATAN_NAMA",
    btrim((pegawai."KPKN_NAMA")::text) AS "KPKN_NAMA",
    btrim((pegawai."INSTANSI_INDUK_NAMA")::text) AS "INSTANSI_INDUK_NAMA",
    btrim((pegawai."INSTANSI_KERJA_NAMA")::text) AS "INSTANSI_KERJA_NAMA",
    btrim((pegawai."SATUAN_KERJA_INDUK_NAMA")::text) AS "SATUAN_KERJA_INDUK_NAMA",
    btrim((pegawai."SATUAN_KERJA_NAMA")::text) AS "SATUAN_KERJA_NAMA",
    btrim((pegawai."JABATAN_INSTANSI_ID")::text) AS "JABATAN_INSTANSI_ID",
    btrim((pegawai."JABATAN_INSTANSI_NAMA")::text) AS "JABATAN_INSTANSI_NAMA",
    pegawai."JENIS_JABATAN_ID",
    pegawai.terminated_date,
    pegawai.status_pegawai,
    btrim((pegawai."JABATAN_PPNPN")::text) AS "JABATAN_PPNPN",
    btrim((jr."NAMA_JABATAN")::text) AS "NAMA_JABATAN_REAL",
    btrim((jr."KATEGORI_JABATAN")::text) AS "KATEGORI_JABATAN_REAL",
    jr."JENIS_JABATAN" AS "JENIS_JABATAN_REAL",
    pegawai."CREATED_DATE",
    pegawai."CREATED_BY",
    pegawai."UPDATED_DATE",
    pegawai."UPDATED_BY",
    btrim((pegawai."EMAIL_DIKBUD")::text) AS "EMAIL_DIKBUD",
    btrim((pegawai."KODECEPAT")::text) AS "KODECEPAT",
    vw."NAMA_UNOR_FULL",
    btrim((golongan."NAMA")::text) AS "NAMA_GOLONGAN",
    btrim((golongan."NAMA_PANGKAT")::text) AS "NAMA_PANGKAT",
    btrim((vw."NAMA_UNOR_ESELON_4")::text) AS "NAMA_UNOR_ESELON_4",
    btrim((vw."NAMA_UNOR_ESELON_3")::text) AS "NAMA_UNOR_ESELON_3",
    btrim((vw."NAMA_UNOR_ESELON_2")::text) AS "NAMA_UNOR_ESELON_2",
    btrim((vw."NAMA_UNOR_ESELON_1")::text) AS "NAMA_UNOR_ESELON_1",
    btrim((un."NAMA_UNOR")::text) AS "UNOR_INDUK_NAMA",
    btrim((vw."ESELON_1")::text) AS "ESELON_1",
    btrim((vw."ESELON_2")::text) AS "ESELON_2",
    btrim((vw."ESELON_3")::text) AS "ESELON_3",
    btrim((vw."ESELON_4")::text) AS "ESELON_4",
    btrim((vw."ESELON_ID")::text) AS "ESELON_ID",
    btrim((kedudukan_hukum."NAMA")::text) AS "KEDUDUKAN_HUKUM_NAMA",
    pa."ID" AS "PNS_AKTIF_ID",
    jr."KELAS" AS "KELAS_JABATAN",
    btrim((agama."NAMA")::text) AS "NAMA_AGAMA"
   FROM ((((((((((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.golongan ON ((pegawai."GOL_ID" = golongan."ID")))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
     LEFT JOIN hris.jabatan ON ((pegawai."JABATAN_INSTANSI_ID" = (jabatan."KODE_JABATAN")::bpchar)))
     LEFT JOIN hris.jabatan jr ON ((pegawai."JABATAN_INSTANSI_REAL_ID" = (jr."KODE_JABATAN")::bpchar)))
     LEFT JOIN hris.pendidikan ON (((pegawai."PENDIDIKAN_ID")::bpchar = (pendidikan."ID")::bpchar)))
     LEFT JOIN hris.tkpendidikan ON (((tkpendidikan."ID")::bpchar = (pendidikan."TINGKAT_PENDIDIKAN_ID")::bpchar)))
     LEFT JOIN hris.unitkerja un ON (((vw."UNOR_INDUK")::text = (un."ID")::text)))
     LEFT JOIN hris.agama ON ((agama."ID" = pegawai."AGAMA_ID")))
     LEFT JOIN hris.kedudukan_hukum ON (((kedudukan_hukum."ID")::text = (pegawai."KEDUDUKAN_HUKUM_ID")::text)))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '14'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '52'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '66'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '67'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '77'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '78'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '98'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '99'::text) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)))
  ORDER BY pegawai."NAMA"
  WITH NO DATA;


ALTER TABLE hris.mv_pegawai OWNER TO postgres;

--
-- TOC entry 613 (class 1259 OID 47730)
-- Name: pegawai_atasan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pegawai_atasan (
    "ID" integer NOT NULL,
    "PNS_NIP" character varying(18),
    "NIP_ATASAN" character varying(18),
    "PPK" character varying(18),
    "KETERANGAN_TAMBAHAN" character varying(255),
    "NAMA_ATASAN" character varying(100),
    "NAMA_PPK" character varying(100)
);


ALTER TABLE hris.pegawai_atasan OWNER TO postgres;

--
-- TOC entry 767 (class 1259 OID 1777738)
-- Name: mv_pegawai_cuti; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_pegawai_cuti AS
 SELECT pegawai."ID",
    pegawai."NIP_BARU",
    pegawai."PNS_ID",
    btrim((pegawai."GELAR_DEPAN")::text) AS "GELAR_DEPAN",
    btrim((pegawai."NAMA")::text) AS "NAMA",
    btrim((pegawai."GELAR_BELAKANG")::text) AS "GELAR_BELAKANG",
    btrim((pegawai."UNOR_INDUK_ID")::text) AS "UNOR_INDUK_ID",
    btrim((pegawai."UNOR_ID")::text) AS "UNOR_ID",
    golongan."NAMA" AS "NAMA_GOLONGAN",
    golongan."NAMA_PANGKAT",
    jabatan."KODE_JABATAN",
    jabatan."NAMA_JABATAN",
    jabatan."KATEGORI_JABATAN",
    btrim((pt."NIP_ATASAN")::text) AS "NIP_ATASAN",
    btrim((pt."NAMA_ATASAN")::text) AS "NAMA_ATASAN",
    btrim((pt."PPK")::text) AS "PPK",
    btrim((pt."NAMA_PPK")::text) AS "NAMA_PPK",
    pt."ID" AS "ID_PEGAWAI_ATASAN",
    pt."KETERANGAN_TAMBAHAN",
    vw."UNOR_INDUK",
    vw."NAMA_UNOR_FULL"
   FROM (((((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.golongan ON ((pegawai."GOL_ID" = golongan."ID")))
     LEFT JOIN hris.jabatan ON ((pegawai."JABATAN_INSTANSI_ID" = (jabatan."KODE_JABATAN")::bpchar)))
     LEFT JOIN hris.pegawai_atasan pt ON (((pegawai."NIP_BARU")::text = (pt."PNS_NIP")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '99'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '66'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '52'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '20'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '04'::text) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)))
  ORDER BY vw."UNOR_INDUK"
  WITH NO DATA;


ALTER TABLE hris.mv_pegawai_cuti OWNER TO postgres;

--
-- TOC entry 768 (class 1259 OID 1777748)
-- Name: mv_pegawai_layanan; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_pegawai_layanan AS
 SELECT pegawai."ID" AS id,
    pegawai."NIP_BARU" AS nip,
    pegawai."NAMA" AS nama,
    pegawai."UNOR_ID" AS unitid,
    pegawai."GOL_ID" AS golid,
    pegawai."JABATAN_INSTANSI_ID" AS jabid,
    pegawai."KEDUDUKAN_HUKUM_ID" AS status_hukum,
    pegawai."TMT_CPNS" AS tmtcpns,
    pegawai."TMT_PNS" AS tmtpns,
    pegawai."MK_TAHUN" AS thn,
    pegawai."MK_BULAN" AS bln,
    pegawai."TMT_PENSIUN" AS tmtpensiun,
    pegawai."GELAR_DEPAN" AS gelar_depan,
    pegawai."GELAR_BELAKANG" AS gelar_belakang,
    pegawai."JENIS_KELAMIN" AS jeniskelamin,
    pegawai."TEMPAT_LAHIR" AS tempatlahir,
    pegawai."TGL_LAHIR" AS tgllahir
   FROM hris.pegawai pegawai
  WITH NO DATA;


ALTER TABLE hris.mv_pegawai_layanan OWNER TO postgres;

--
-- TOC entry 468 (class 1259 OID 36202)
-- Name: rwt_assesmen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_assesmen (
    "ID" integer NOT NULL,
    "PNS_ID" character(255),
    "PNS_NIP" character(25),
    "TAHUN" character(4),
    "FILE_UPLOAD" character(100),
    "NILAI" real,
    "NILAI_KINERJA" real,
    "TAHUN_PENILAIAN_ID" character varying(32),
    "TAHUN_PENILAIAN_TITLE" character varying(20) NOT NULL,
    "FULLNAME" character varying(255),
    "POSISI_ID" character varying(20),
    "UNIT_ORG_ID" character varying(50),
    "NAMA_UNOR" character varying(200),
    "SARANPENGEMBANGAN" text,
    "FILE_UPLOAD_FB_POTENSI" character varying(255),
    "FILE_UPLOAD_LENGKAP_PT" character varying(255),
    "FILE_UPLOAD_FB_PT" character varying(255),
    "FILE_UPLOAD_EXISTS" smallint DEFAULT 0,
    "SATKER_ID" character varying
);


ALTER TABLE hris.rwt_assesmen OWNER TO postgres;

--
-- TOC entry 6221 (class 0 OID 0)
-- Dependencies: 468
-- Name: COLUMN rwt_assesmen."TAHUN"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.rwt_assesmen."TAHUN" IS 'tahun_penilaian_awal';


--
-- TOC entry 6222 (class 0 OID 0)
-- Dependencies: 468
-- Name: COLUMN rwt_assesmen."FILE_UPLOAD"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.rwt_assesmen."FILE_UPLOAD" IS 'file laporan lengkap potensi';


--
-- TOC entry 6223 (class 0 OID 0)
-- Dependencies: 468
-- Name: COLUMN rwt_assesmen."NILAI"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.rwt_assesmen."NILAI" IS 'Nilai Potensi';


--
-- TOC entry 646 (class 1259 OID 263182)
-- Name: mv_riwayat_asesmen; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_riwayat_asesmen AS
 SELECT btrim((rwt_assesmen."PNS_NIP")::text) AS "PNS_NIP",
    btrim((rwt_assesmen."TAHUN")::text) AS "TAHUN",
    rwt_assesmen."NILAI",
    btrim((rwt_assesmen."FILE_UPLOAD")::text) AS "FILE_UPLOAD_FB_POTENSI",
    btrim((rwt_assesmen."FILE_UPLOAD")::text) AS "FILE_UPLOAD_LENGKAP_PT",
    btrim((rwt_assesmen."FILE_UPLOAD")::text) AS "FILE_UPLOAD_FB_PT"
   FROM hris.rwt_assesmen
  WHERE ((btrim((rwt_assesmen."TAHUN")::text) = ('2019'::bpchar)::text) AND (rwt_assesmen."FILE_UPLOAD" ~~* '%a_p%'::text) AND (rwt_assesmen."FILE_UPLOAD_EXISTS" = '1'::smallint))
UNION ALL
 SELECT btrim((rwt_assesmen."PNS_NIP")::text) AS "PNS_NIP",
    rwt_assesmen."TAHUN",
    rwt_assesmen."NILAI",
    btrim((rwt_assesmen."FILE_UPLOAD_FB_POTENSI")::text) AS "FILE_UPLOAD_FB_POTENSI",
    btrim((rwt_assesmen."FILE_UPLOAD_LENGKAP_PT")::text) AS "FILE_UPLOAD_LENGKAP_PT",
    btrim((rwt_assesmen."FILE_UPLOAD_FB_PT")::text) AS "FILE_UPLOAD_FB_PT"
   FROM hris.rwt_assesmen
  WHERE (btrim((rwt_assesmen."TAHUN")::text) <> ('2019'::bpchar)::text)
  WITH NO DATA;


ALTER TABLE hris.mv_riwayat_asesmen OWNER TO postgres;

--
-- TOC entry 728 (class 1259 OID 1272622)
-- Name: mv_unit_list_all; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.mv_unit_list_all AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(32) AS "ESELON_1",
    (x.eselon[2])::character varying(32) AS "ESELON_2",
    (x.eselon[3])::character varying(32) AS "ESELON_3",
    (x.eselon[4])::character varying(32) AS "ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WITH NO DATA;


ALTER TABLE hris.mv_unit_list_all OWNER TO postgres;

--
-- TOC entry 428 (class 1259 OID 36043)
-- Name: nama_unit; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.nama_unit AS
 SELECT a."ID",
    a."NAMA_UNOR",
    ( SELECT unitkerja."NAMA_UNOR"
           FROM hris.unitkerja_1234 unitkerja
          WHERE ((unitkerja."ID")::text = (a."UNOR_INDUK")::text)) AS satker,
    ( SELECT unitkerja."NAMA_UNOR"
           FROM hris.unitkerja_1234 unitkerja
          WHERE ((unitkerja."ID")::text = (a."ESELON_4")::text)) AS es4,
    ( SELECT unitkerja."NAMA_UNOR"
           FROM hris.unitkerja_1234 unitkerja
          WHERE ((unitkerja."ID")::text = (a."ESELON_3")::text)) AS es3,
    ( SELECT unitkerja."NAMA_UNOR"
           FROM hris.unitkerja_1234 unitkerja
          WHERE ((unitkerja."ID")::text = (a."ESELON_2")::text)) AS es2,
    ( SELECT unitkerja."NAMA_UNOR"
           FROM hris.unitkerja_1234 unitkerja
          WHERE ((unitkerja."ID")::text = (a."ESELON_1")::text)) AS es1
   FROM hris.unitkerja_1234 a;


ALTER TABLE hris.nama_unit OWNER TO postgres;

--
-- TOC entry 429 (class 1259 OID 36047)
-- Name: nip_pejabat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.nip_pejabat (
    "NIP" character varying(18) NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE hris.nip_pejabat OWNER TO postgres;

--
-- TOC entry 430 (class 1259 OID 36050)
-- Name: nip_pejabat_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.nip_pejabat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.nip_pejabat_id_seq OWNER TO postgres;

--
-- TOC entry 6228 (class 0 OID 0)
-- Dependencies: 430
-- Name: nip_pejabat_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.nip_pejabat_id_seq OWNED BY hris.nip_pejabat.id;


--
-- TOC entry 431 (class 1259 OID 36052)
-- Name: orang_tua; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.orang_tua (
    "ID" integer NOT NULL,
    "HUBUNGAN" smallint,
    "ALAMAT" text,
    "NO_TLP" character varying(30),
    "NO_HP" character varying(50),
    "STATUS_PERKAWINAN" character varying(20),
    "AKTE_KELAHIRAN" character varying(255),
    "STATUS_HIDUP" smallint,
    "AKTE_MENINGGAL" character varying(255),
    "TGL_MENINGGAL" date,
    "NO_NPWP" character varying(255),
    "TANGGAL_NPWP" date,
    "NAMA" character varying(255),
    "GELAR_DEPAN" character varying(20),
    "GELAR_BELAKANG" character varying(50),
    "TEMPAT_LAHIR" character varying(255),
    "TANGGAL_LAHIR" character varying(255),
    "JENIS_KELAMIN" character varying(20),
    "AGAMA" character varying(2),
    "EMAIL" character varying(255),
    "JENIS_DOKUMEN_ID" character varying(10),
    "NO_DOKUMEN_ID" character varying(50),
    "FOTO" character varying(255),
    "KODE" smallint,
    "NIP" character varying(32),
    "PNS_ID" character varying(32)
);


ALTER TABLE hris.orang_tua OWNER TO postgres;

--
-- TOC entry 6229 (class 0 OID 0)
-- Dependencies: 431
-- Name: COLUMN orang_tua."KODE"; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.orang_tua."KODE" IS '1=AYAH
2=IBU';


--
-- TOC entry 432 (class 1259 OID 36058)
-- Name: orang_tua_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."orang_tua_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."orang_tua_ID_seq" OWNER TO postgres;

--
-- TOC entry 6231 (class 0 OID 0)
-- Dependencies: 432
-- Name: orang_tua_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."orang_tua_ID_seq" OWNED BY hris.orang_tua."ID";


--
-- TOC entry 433 (class 1259 OID 36060)
-- Name: organisasi_ropeg; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.organisasi_ropeg AS
 WITH RECURSIVE org AS (
         SELECT unitkerja."ID" AS idorg,
            unitkerja."DIATASAN_ID" AS idorgparent,
            unitkerja."NAMA_UNOR" AS orgname,
            unitkerja."PEMIMPIN_PNS_ID" AS pemimpinpnsid
           FROM hris.unitkerja
          WHERE ((unitkerja."ID")::text = '8ae483a8641f817901641fce97d21d1b'::text)
        UNION
         SELECT e."ID",
            e."DIATASAN_ID",
            e."NAMA_UNOR",
            e."PEMIMPIN_PNS_ID"
           FROM (hris.unitkerja e
             JOIN org s ON (((s.idorg)::text = (e."DIATASAN_ID")::text)))
        )
 SELECT x.idorg,
    x.idorgparent,
    x.orgname,
    x.pemimpinpnsid
   FROM org x
  ORDER BY x.idorg;


ALTER TABLE hris.organisasi_ropeg OWNER TO postgres;

--
-- TOC entry 644 (class 1259 OID 262742)
-- Name: pegawai_08_sept_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pegawai_08_sept_2020 (
    "ID" integer,
    "PNS_ID" character varying(32),
    "NIP_LAMA" character varying(9),
    "NIP_BARU" character varying(18),
    "NAMA" character varying(255),
    "GELAR_DEPAN" character varying(60),
    "GELAR_BELAKANG" character varying(60),
    "TEMPAT_LAHIR_ID" character varying(50),
    "TGL_LAHIR" date,
    "JENIS_KELAMIN" character varying(10),
    "AGAMA_ID" integer,
    "JENIS_KAWIN_ID" character varying(255),
    "NIK" character varying(255),
    "NOMOR_DARURAT" character varying(255),
    "NOMOR_HP" character varying(60),
    "EMAIL" character varying(255),
    "ALAMAT" character varying(255),
    "NPWP" character varying(255),
    "BPJS" character varying(50),
    "JENIS_PEGAWAI_ID" character varying(50),
    "KEDUDUKAN_HUKUM_ID" character varying(32),
    "STATUS_CPNS_PNS" character varying(20),
    "KARTU_PEGAWAI" character varying(30),
    "NOMOR_SK_CPNS" character varying(60),
    "TGL_SK_CPNS" date,
    "TMT_CPNS" date,
    "TMT_PNS" date,
    "GOL_AWAL_ID" character varying(32),
    "GOL_ID" integer,
    "TMT_GOLONGAN" date,
    "MK_TAHUN" character varying(20),
    "MK_BULAN" character varying(20),
    "JENIS_JABATAN_IDx" character varying(32),
    "JABATAN_ID" character varying(32),
    "TMT_JABATAN" date,
    "PENDIDIKAN_ID" character varying(32),
    "TAHUN_LULUS" character varying(20),
    "KPKN_ID" character varying(32),
    "LOKASI_KERJA_ID" character varying(32),
    "UNOR_ID" character varying(36),
    "UNOR_INDUK_ID" character varying(36),
    "INSTANSI_INDUK_ID" character varying(32),
    "INSTANSI_KERJA_ID" character varying(32),
    "SATUAN_KERJA_INDUK_ID" character varying(32),
    "SATUAN_KERJA_KERJA_ID" character varying(32),
    "GOLONGAN_DARAH" character varying(20),
    "PHOTO" character varying(100),
    "TMT_PENSIUN" date,
    "LOKASI_KERJA" character(200),
    "JML_ISTRI" character(1),
    "JML_ANAK" character(1),
    "NO_SURAT_DOKTER" character(100),
    "TGL_SURAT_DOKTER" date,
    "NO_BEBAS_NARKOBA" character(100),
    "TGL_BEBAS_NARKOBA" date,
    "NO_CATATAN_POLISI" character(100),
    "TGL_CATATAN_POLISI" date,
    "AKTE_KELAHIRAN" character(50),
    "STATUS_HIDUP" character(15),
    "AKTE_MENINGGAL" character(50),
    "TGL_MENINGGAL" date,
    "NO_ASKES" character(50),
    "NO_TASPEN" character(50),
    "TGL_NPWP" date,
    "TEMPAT_LAHIR" character(200),
    "PENDIDIKAN" character(165),
    "TK_PENDIDIKAN" character(3),
    "TEMPAT_LAHIR_NAMA" character(200),
    "JENIS_JABATAN_NAMA" character(200),
    "JABATAN_NAMA" character(254),
    "KPKN_NAMA" character(255),
    "INSTANSI_INDUK_NAMA" character(100),
    "INSTANSI_KERJA_NAMA" character(160),
    "SATUAN_KERJA_INDUK_NAMA" character(170),
    "SATUAN_KERJA_NAMA" character(155),
    "JABATAN_INSTANSI_ID" character(15),
    "BUP" smallint,
    "JABATAN_INSTANSI_NAMA" character varying(512),
    "JENIS_JABATAN_ID" integer,
    terminated_date date,
    status_pegawai smallint,
    "JABATAN_PPNPN" character(255),
    "JABATAN_INSTANSI_REAL_ID" character(15),
    "CREATED_DATE" date,
    "CREATED_BY" integer,
    "UPDATED_DATE" date,
    "UPDATED_BY" integer,
    "EMAIL_DIKBUD_BAK" character varying(255),
    "EMAIL_DIKBUD" character varying(100),
    "KODECEPAT" character varying(100)
);


ALTER TABLE hris.pegawai_08_sept_2020 OWNER TO postgres;

--
-- TOC entry 609 (class 1259 OID 47711)
-- Name: pegawai_atasan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."pegawai_atasan_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."pegawai_atasan_ID_seq" OWNER TO postgres;

--
-- TOC entry 6234 (class 0 OID 0)
-- Dependencies: 609
-- Name: pegawai_atasan_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."pegawai_atasan_ID_seq" OWNED BY hris.pegawai_atasan."ID";


--
-- TOC entry 434 (class 1259 OID 36065)
-- Name: pegawai_bkn_id; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.pegawai_bkn_id
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.pegawai_bkn_id OWNER TO postgres;

--
-- TOC entry 435 (class 1259 OID 36067)
-- Name: pegawai_bkn; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pegawai_bkn (
    "ID" integer DEFAULT nextval('hris.pegawai_bkn_id'::regclass) NOT NULL,
    "PNS_ID" character varying(32) NOT NULL,
    "NIP_LAMA" character varying(9),
    "NIP_BARU" character varying(18),
    "NAMA" character varying(255),
    "GELAR_DEPAN" character varying(60),
    "GELAR_BELAKANG" character varying(60),
    "TEMPAT_LAHIR_ID" character varying(50),
    "TGL_LAHIR" date,
    "JENIS_KELAMIN" character varying(10),
    "AGAMA_ID" integer,
    "JENIS_KAWIN_ID" character varying(255),
    "NIK" character varying(255),
    "NOMOR_DARURAT" character varying(255),
    "NOMOR_HP" character varying(60),
    "EMAIL" character varying(255),
    "ALAMAT" character varying(255),
    "NPWP" character varying(255),
    "BPJS" character varying(50),
    "JENIS_PEGAWAI_ID" character varying(50),
    "KEDUDUKAN_HUKUM_ID" character varying(32),
    "STATUS_CPNS_PNS" character varying(20),
    "KARTU_PEGAWAI" character varying(30),
    "NOMOR_SK_CPNS" character varying(60),
    "TGL_SK_CPNS" date,
    "TMT_CPNS" date,
    "TMT_PNS" date,
    "GOL_AWAL_ID" character varying(32),
    "GOL_ID" integer,
    "TMT_GOLONGAN" date,
    "MK_TAHUN" character varying(20),
    "MK_BULAN" character varying(20),
    "JENIS_JABATAN_IDx" character varying(32),
    "JABATAN_ID" character varying(32),
    "TMT_JABATAN" date,
    "PENDIDIKAN_ID" character varying(32),
    "TAHUN_LULUS" character varying(20),
    "KPKN_ID" character varying(32),
    "LOKASI_KERJA_ID" character varying(32),
    "UNOR_ID" character varying(32),
    "UNOR_INDUK_ID" character varying(32),
    "INSTANSI_INDUK_ID" character varying(32),
    "INSTANSI_KERJA_ID" character varying(32),
    "SATUAN_KERJA_INDUK_ID" character varying(32),
    "SATUAN_KERJA_KERJA_ID" character varying(32),
    "GOLONGAN_DARAH" character varying(20),
    "PHOTO" character varying(100),
    "TMT_PENSIUN" date,
    "LOKASI_KERJA" character(200),
    "JML_ISTRI" character(1),
    "JML_ANAK" character(1),
    "NO_SURAT_DOKTER" character(100),
    "TGL_SURAT_DOKTER" date,
    "NO_BEBAS_NARKOBA" character(100),
    "TGL_BEBAS_NARKOBA" date,
    "NO_CATATAN_POLISI" character(100),
    "TGL_CATATAN_POLISI" date,
    "AKTE_KELAHIRAN" character(50),
    "STATUS_HIDUP" character(15),
    "AKTE_MENINGGAL" character(50),
    "TGL_MENINGGAL" date,
    "NO_ASKES" character(50),
    "NO_TASPEN" character(50),
    "TGL_NPWP" date,
    "TEMPAT_LAHIR" character(200),
    "PENDIDIKAN" character(165),
    "TK_PENDIDIKAN" character(3),
    "TEMPAT_LAHIR_NAMA" character(200),
    "JENIS_JABATAN_NAMA" character(200),
    "JABATAN_NAMA" character(254),
    "KPKN_NAMA" character(255),
    "INSTANSI_INDUK_NAMA" character(100),
    "INSTANSI_KERJA_NAMA" character(160),
    "SATUAN_KERJA_INDUK_NAMA" character(170),
    "SATUAN_KERJA_NAMA" character(155),
    "JABATAN_INSTANSI_ID" character(15),
    "BUP" smallint DEFAULT 58,
    "JABATAN_INSTANSI_NAMA" character varying(512) DEFAULT NULL::character varying,
    "JENIS_JABATAN_ID" integer
);


ALTER TABLE hris.pegawai_bkn OWNER TO postgres;

--
-- TOC entry 436 (class 1259 OID 36076)
-- Name: pegawai_copy; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pegawai_copy (
    "ID" integer DEFAULT nextval('hris.pegawai_id_seq'::regclass) NOT NULL,
    "PNS_ID" character varying(32) NOT NULL,
    "NIP_LAMA" character varying(9),
    "NIP_BARU" character varying(18),
    "NAMA" character varying(255),
    "GELAR_DEPAN" character varying(60),
    "GELAR_BELAKANG" character varying(60),
    "TEMPAT_LAHIR_ID" character varying(50),
    "TGL_LAHIR" date,
    "JENIS_KELAMIN" character varying(10),
    "AGAMA_ID" integer,
    "JENIS_KAWIN_ID" character varying(255),
    "NIK" character varying(255),
    "NOMOR_DARURAT" character varying(255),
    "NOMOR_HP" character varying(60),
    "EMAIL" character varying(255),
    "ALAMAT" character varying(255),
    "NPWP" character varying(255),
    "BPJS" character varying(50),
    "JENIS_PEGAWAI_ID" character varying(50),
    "KEDUDUKAN_HUKUM_ID" character varying(32),
    "STATUS_CPNS_PNS" character varying(20),
    "KARTU_PEGAWAI" character varying(30),
    "NOMOR_SK_CPNS" character varying(60),
    "TGL_SK_CPNS" date,
    "TMT_CPNS" date,
    "TMT_PNS" date,
    "GOL_AWAL_ID" character varying(32),
    "GOL_ID" integer,
    "TMT_GOLONGAN" date,
    "MK_TAHUN" character varying(20),
    "MK_BULAN" character varying(20),
    "JENIS_JABATAN_IDx" character varying(32),
    "JABATAN_ID" character varying(32),
    "TMT_JABATAN" date,
    "PENDIDIKAN_ID" character varying(32),
    "TAHUN_LULUS" character varying(20),
    "KPKN_ID" character varying(32),
    "LOKASI_KERJA_ID" character varying(32),
    "UNOR_ID" character varying(32),
    "UNOR_INDUK_ID" character varying(32),
    "INSTANSI_INDUK_ID" character varying(32),
    "INSTANSI_KERJA_ID" character varying(32),
    "SATUAN_KERJA_INDUK_ID" character varying(32),
    "SATUAN_KERJA_KERJA_ID" character varying(32),
    "GOLONGAN_DARAH" character varying(20),
    "PHOTO" character varying(100),
    "TMT_PENSIUN" date,
    "LOKASI_KERJA" character(200),
    "JML_ISTRI" character(1),
    "JML_ANAK" character(1),
    "NO_SURAT_DOKTER" character(100),
    "TGL_SURAT_DOKTER" date,
    "NO_BEBAS_NARKOBA" character(100),
    "TGL_BEBAS_NARKOBA" date,
    "NO_CATATAN_POLISI" character(100),
    "TGL_CATATAN_POLISI" date,
    "AKTE_KELAHIRAN" character(50),
    "STATUS_HIDUP" character(15),
    "AKTE_MENINGGAL" character(50),
    "TGL_MENINGGAL" date,
    "NO_ASKES" character(50),
    "NO_TASPEN" character(50),
    "TGL_NPWP" date,
    "TEMPAT_LAHIR" character(200),
    "PENDIDIKAN" character(165),
    "TK_PENDIDIKAN" character(3),
    "TEMPAT_LAHIR_NAMA" character(200),
    "JENIS_JABATAN_NAMA" character(200),
    "JABATAN_NAMA" character(254),
    "KPKN_NAMA" character(255),
    "INSTANSI_INDUK_NAMA" character(100),
    "INSTANSI_KERJA_NAMA" character(160),
    "SATUAN_KERJA_INDUK_NAMA" character(170),
    "SATUAN_KERJA_NAMA" character(155),
    "JABATAN_INSTANSI_ID" character(15),
    "BUP" smallint DEFAULT 58,
    "JABATAN_INSTANSI_NAMA" character varying(512) DEFAULT NULL::character varying,
    "JENIS_JABATAN_ID" integer,
    terminated_date date,
    status_pegawai smallint DEFAULT 1
);


ALTER TABLE hris.pegawai_copy OWNER TO postgres;

--
-- TOC entry 6236 (class 0 OID 0)
-- Dependencies: 436
-- Name: COLUMN pegawai_copy.status_pegawai; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.pegawai_copy.status_pegawai IS '1=pns,2=honorer';


--
-- TOC entry 437 (class 1259 OID 36086)
-- Name: pengajuan_tubel; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pengajuan_tubel (
    "ID" integer NOT NULL,
    "NIP" character varying(30),
    "NOMOR_USUL" character varying(20),
    "TANGGAL_USUL" date,
    "UNIVERSITAS" character varying(100),
    "FAKULTAS" character varying(100),
    "PRODI" character varying(100),
    "BEASISWA" integer,
    "PEMBERI_BEASISWA" character varying(100),
    "JENJANG" character varying(5),
    "NEGARA" character varying(50),
    "STATUS" integer DEFAULT 1,
    "ALASAN_DITOLAK" character varying(255),
    "MULAI_BELAJAR" date,
    "AKHIR_BELAJAR" date
);


ALTER TABLE hris.pengajuan_tubel OWNER TO postgres;

--
-- TOC entry 438 (class 1259 OID 36093)
-- Name: pengajuan_tubel_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."pengajuan_tubel_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."pengajuan_tubel_ID_seq" OWNER TO postgres;

--
-- TOC entry 6239 (class 0 OID 0)
-- Dependencies: 438
-- Name: pengajuan_tubel_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."pengajuan_tubel_ID_seq" OWNED BY hris.pengajuan_tubel."ID";


--
-- TOC entry 693 (class 1259 OID 522554)
-- Name: peraturan_otk_id_peraturan_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.peraturan_otk_id_peraturan_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.peraturan_otk_id_peraturan_seq OWNER TO postgres;

--
-- TOC entry 6240 (class 0 OID 0)
-- Dependencies: 693
-- Name: peraturan_otk_id_peraturan_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.peraturan_otk_id_peraturan_seq OWNED BY hris.mst_peraturan_otk.id_peraturan;


--
-- TOC entry 439 (class 1259 OID 36095)
-- Name: perkiraan_kpo; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.perkiraan_kpo (
    id bigint NOT NULL,
    nip character varying(255),
    status smallint,
    alasan text,
    layanan_id bigint,
    nama character varying(255),
    birth_place character varying(255),
    birth_date date,
    last_edu character varying(255),
    o_gol_ruang character varying(255),
    o_gol_tmt date,
    o_masakerja_thn smallint,
    o_masakerja_bln smallint,
    o_gapok double precision,
    o_jabatan character varying(255),
    o_tmt_jabatan date,
    n_gol_ruang character varying(255),
    n_gol_tmt date,
    n_masakerja_thn smallint,
    n_masakerja_bln smallint,
    n_gapok double precision,
    n_jabatan character varying(255),
    n_tmt_jabatan date,
    unit_kerja character varying(255),
    unit_kerja_induk character varying(255),
    kantor_pembayaran character varying(255),
    tahun_lulus smallint,
    no_surat_pengantar character varying(255),
    no_surat_pengantar_es1 character varying(255)
);


ALTER TABLE hris.perkiraan_kpo OWNER TO postgres;

--
-- TOC entry 6241 (class 0 OID 0)
-- Dependencies: 439
-- Name: COLUMN perkiraan_kpo.status; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.perkiraan_kpo.status IS 'tms/ms';


--
-- TOC entry 440 (class 1259 OID 36101)
-- Name: perkiraan_kpo_documents_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.perkiraan_kpo_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.perkiraan_kpo_documents_id_seq OWNER TO postgres;

--
-- TOC entry 441 (class 1259 OID 36103)
-- Name: perkiraan_kpo_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.perkiraan_kpo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.perkiraan_kpo_id_seq OWNER TO postgres;

--
-- TOC entry 6243 (class 0 OID 0)
-- Dependencies: 441
-- Name: perkiraan_kpo_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.perkiraan_kpo_id_seq OWNED BY hris.perkiraan_kpo.id;


--
-- TOC entry 442 (class 1259 OID 36105)
-- Name: perkiraan_ppo_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.perkiraan_ppo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.perkiraan_ppo_id_seq OWNER TO postgres;

--
-- TOC entry 443 (class 1259 OID 36107)
-- Name: perkiraan_ppo; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.perkiraan_ppo (
    id bigint DEFAULT nextval('hris.perkiraan_ppo_id_seq'::regclass) NOT NULL,
    nip character varying(255),
    status smallint DEFAULT 1,
    alasan text,
    layanan_id bigint,
    nama character varying(255),
    birth_place character varying(255),
    birth_date date,
    last_edu character varying(255),
    o_gol_ruang character varying(255),
    o_gol_tmt date,
    o_masakerja_thn smallint,
    o_masakerja_bln smallint,
    o_gapok double precision,
    o_jabatan character varying(255),
    o_tmt_jabatan date,
    n_gol_ruang character varying(255),
    n_gol_tmt date,
    n_masakerja_thn smallint,
    n_masakerja_bln smallint,
    n_gapok double precision,
    n_jabatan character varying(255),
    n_tmt_jabatan date,
    unit_kerja character varying(255),
    unit_kerja_induk character varying(255),
    kantor_pembayaran character varying(255),
    tahun_lulus smallint,
    no_surat_pengantar character varying(255),
    bup smallint,
    n_jabatan_id character varying
);


ALTER TABLE hris.perkiraan_ppo OWNER TO postgres;

--
-- TOC entry 6244 (class 0 OID 0)
-- Dependencies: 443
-- Name: COLUMN perkiraan_ppo.status; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.perkiraan_ppo.status IS 'tms/ms';


--
-- TOC entry 444 (class 1259 OID 36115)
-- Name: perkiraan_usulan_log; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.perkiraan_usulan_log (
    id bigint NOT NULL,
    usulan_id bigint,
    _created_at timestamp(6) without time zone DEFAULT now(),
    _created_by character varying(255),
    status character varying(255),
    alasan character varying(255)
);


ALTER TABLE hris.perkiraan_usulan_log OWNER TO postgres;

--
-- TOC entry 445 (class 1259 OID 36122)
-- Name: perkiraan_usulan_log_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.perkiraan_usulan_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.perkiraan_usulan_log_id_seq OWNER TO postgres;

--
-- TOC entry 6247 (class 0 OID 0)
-- Dependencies: 445
-- Name: perkiraan_usulan_log_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.perkiraan_usulan_log_id_seq OWNED BY hris.perkiraan_usulan_log.id;


--
-- TOC entry 446 (class 1259 OID 36124)
-- Name: permissions_permission_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.permissions_permission_id_seq
    START WITH 219
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.permissions_permission_id_seq OWNER TO postgres;

--
-- TOC entry 447 (class 1259 OID 36126)
-- Name: permissions; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.permissions (
    permission_id bigint DEFAULT nextval('hris.permissions_permission_id_seq'::regclass) NOT NULL,
    name character varying(100),
    description character varying(255),
    status character varying(20)
);


ALTER TABLE hris.permissions OWNER TO postgres;

--
-- TOC entry 698 (class 1259 OID 522572)
-- Name: peta_jabatan_permen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.peta_jabatan_permen (
    id smallint NOT NULL,
    permen character varying(50)
);


ALTER TABLE hris.peta_jabatan_permen OWNER TO postgres;

--
-- TOC entry 694 (class 1259 OID 522556)
-- Name: peta_jabatan_permen_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.peta_jabatan_permen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.peta_jabatan_permen_id_seq OWNER TO postgres;

--
-- TOC entry 6249 (class 0 OID 0)
-- Dependencies: 694
-- Name: peta_jabatan_permen_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.peta_jabatan_permen_id_seq OWNED BY hris.peta_jabatan_permen.id;


--
-- TOC entry 448 (class 1259 OID 36130)
-- Name: pindah_unit; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.pindah_unit (
    "ID" integer NOT NULL,
    "NIP" character varying(32) NOT NULL,
    "SURAT_PERMOHONAN_PINDAH" character varying(100),
    "UNIT_ASAL" character varying(32),
    "UNIT_TUJUAN" character varying(32),
    "SURAT_PERNYATAAN_MELEPAS" character varying(100),
    "SK_KP_TERAKHIR" character varying(100),
    "SK_JABATAN" character varying(100),
    "SKP" character varying(10),
    "SK_TUNKIN" character varying(100),
    "SURAT_PERNYATAAN_MENERIMA" character varying(100),
    "NO_SK_PINDAH" character varying(100),
    "TANGGAL_SK_PINDAH" character varying(10),
    "FILE_SK" character varying(100),
    "STATUS_SATKER" integer,
    "STATUS_BIRO" integer,
    "JABATAN_ID" numeric,
    "KETERANGAN" character(255),
    "TANGGAL_TMT_PINDAH" date,
    "CREATED_DATE" date,
    "CREATED_BY" integer
);


ALTER TABLE hris.pindah_unit OWNER TO postgres;

--
-- TOC entry 449 (class 1259 OID 36136)
-- Name: pindah_unit_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."pindah_unit_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."pindah_unit_ID_seq" OWNER TO postgres;

--
-- TOC entry 6251 (class 0 OID 0)
-- Dependencies: 449
-- Name: pindah_unit_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."pindah_unit_ID_seq" OWNED BY hris.pindah_unit."ID";


--
-- TOC entry 450 (class 1259 OID 36138)
-- Name: pns_aktif_old; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.pns_aktif_old AS
 SELECT temp."ID",
    temp.masa_kerja[0] AS masa_kerja_th,
    temp.masa_kerja[1] AS masa_kerja_bl
   FROM ( SELECT pegawai."ID",
            hris.get_masa_kerja_arr(pegawai."TMT_CPNS", ('now'::text)::date) AS masa_kerja
           FROM hris.pegawai
          WHERE ((pegawai.status_pegawai = 1) AND ((pegawai.terminated_date IS NULL) OR ((pegawai.terminated_date IS NOT NULL) AND (pegawai.terminated_date > ('now'::text)::date))))) temp;


ALTER TABLE hris.pns_aktif_old OWNER TO postgres;

--
-- TOC entry 451 (class 1259 OID 36143)
-- Name: ref_jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.ref_jabatan (
    "ID_JABATAN" double precision NOT NULL,
    "NAMA_JABATAN" text,
    "JENIS_JABATAN" character varying(100),
    "KELAS" smallint,
    "PENSIUN" smallint,
    "KODE_BKN" character varying(250),
    "TUNJANGAN" double precision
);


ALTER TABLE hris.ref_jabatan OWNER TO postgres;

--
-- TOC entry 452 (class 1259 OID 36149)
-- Name: ref_tunjangan_jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.ref_tunjangan_jabatan (
    "ID_TUNJAB" integer NOT NULL,
    "ESELON" character varying(10),
    "BESARAN_TUNJAB" character varying(100)
);


ALTER TABLE hris.ref_tunjangan_jabatan OWNER TO postgres;

--
-- TOC entry 453 (class 1259 OID 36152)
-- Name: ref_tunjangan_kinerja; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.ref_tunjangan_kinerja (
    "ID" integer NOT NULL,
    "KELAS_JABATAN" integer,
    "TUNJANGAN_KINERJA" double precision
);


ALTER TABLE hris.ref_tunjangan_kinerja OWNER TO postgres;

--
-- TOC entry 454 (class 1259 OID 36155)
-- Name: ref_tunjangan_kinerja_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."ref_tunjangan_kinerja_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."ref_tunjangan_kinerja_ID_seq" OWNER TO postgres;

--
-- TOC entry 6256 (class 0 OID 0)
-- Dependencies: 454
-- Name: ref_tunjangan_kinerja_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."ref_tunjangan_kinerja_ID_seq" OWNED BY hris.ref_tunjangan_kinerja."ID";


--
-- TOC entry 455 (class 1259 OID 36157)
-- Name: rekap_agama_jenis_kelamin; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.rekap_agama_jenis_kelamin AS
 SELECT pegawai."JENIS_KELAMIN",
    agama."ID",
    agama."NAMA",
    count(*) AS total
   FROM (hris.pegawai
     LEFT JOIN hris.agama ON ((pegawai."AGAMA_ID" = agama."ID")))
  GROUP BY pegawai."JENIS_KELAMIN", agama."ID", agama."NAMA"
  ORDER BY agama."NAMA";


ALTER TABLE hris.rekap_agama_jenis_kelamin OWNER TO postgres;

--
-- TOC entry 699 (class 1259 OID 522578)
-- Name: request_formasi; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.request_formasi (
    id bigint NOT NULL,
    unit_id character varying(32),
    jumlah_ajuan smallint,
    kualifikasi_pendidikan text,
    id_jabatan character varying(32),
    satker_id character varying(32),
    tahun character varying(4),
    skala_prioritas smallint
);


ALTER TABLE hris.request_formasi OWNER TO postgres;

--
-- TOC entry 695 (class 1259 OID 522558)
-- Name: request_formasi_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.request_formasi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.request_formasi_id_seq OWNER TO postgres;

--
-- TOC entry 6258 (class 0 OID 0)
-- Dependencies: 695
-- Name: request_formasi_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.request_formasi_id_seq OWNED BY hris.request_formasi.id;


--
-- TOC entry 456 (class 1259 OID 36162)
-- Name: role_permissions; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.role_permissions (
    role_id bigint,
    permission_id bigint,
    id bigint NOT NULL
);


ALTER TABLE hris.role_permissions OWNER TO postgres;

--
-- TOC entry 457 (class 1259 OID 36165)
-- Name: role_permissions_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.role_permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.role_permissions_id_seq OWNER TO postgres;

--
-- TOC entry 6260 (class 0 OID 0)
-- Dependencies: 457
-- Name: role_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.role_permissions_id_seq OWNED BY hris.role_permissions.id;


--
-- TOC entry 458 (class 1259 OID 36167)
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.roles_role_id_seq
    START WITH 6
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.roles_role_id_seq OWNER TO postgres;

--
-- TOC entry 459 (class 1259 OID 36169)
-- Name: roles; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.roles (
    role_name character(60) NOT NULL,
    description character varying(255) DEFAULT NULL::character varying,
    is_default integer DEFAULT 0 NOT NULL,
    can_delete integer DEFAULT 1 NOT NULL,
    login_destination character(255) DEFAULT '/'::bpchar NOT NULL,
    deleted integer DEFAULT 0 NOT NULL,
    default_context character(255) DEFAULT 'content'::bpchar NOT NULL,
    role_id integer DEFAULT nextval('hris.roles_role_id_seq'::regclass) NOT NULL
);


ALTER TABLE hris.roles OWNER TO postgres;

--
-- TOC entry 460 (class 1259 OID 36182)
-- Name: roles_users; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.roles_users (
    role_id bigint,
    user_id bigint,
    role_user_id bigint NOT NULL
);


ALTER TABLE hris.roles_users OWNER TO postgres;

--
-- TOC entry 682 (class 1259 OID 407342)
-- Name: roles_users_role_user_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.roles_users_role_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.roles_users_role_user_id_seq OWNER TO postgres;

--
-- TOC entry 6263 (class 0 OID 0)
-- Dependencies: 682
-- Name: roles_users_role_user_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.roles_users_role_user_id_seq OWNED BY hris.roles_users.role_user_id;


--
-- TOC entry 461 (class 1259 OID 36185)
-- Name: rpt_golongan_bulan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rpt_golongan_bulan (
    "ID" smallint NOT NULL,
    "GOLONGAN_ID" character varying(10),
    "GOLONGAN_NAMA" character varying(40),
    "BULAN" smallint,
    "TAHUN" smallint,
    "JUMLAH" smallint
);


ALTER TABLE hris.rpt_golongan_bulan OWNER TO postgres;

--
-- TOC entry 462 (class 1259 OID 36188)
-- Name: rpt_golongan_bulan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rpt_golongan_bulan_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rpt_golongan_bulan_ID_seq" OWNER TO postgres;

--
-- TOC entry 6265 (class 0 OID 0)
-- Dependencies: 462
-- Name: rpt_golongan_bulan_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rpt_golongan_bulan_ID_seq" OWNED BY hris.rpt_golongan_bulan."ID";


--
-- TOC entry 463 (class 1259 OID 36190)
-- Name: rpt_jumlah_asn; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rpt_jumlah_asn (
    "ID" integer NOT NULL,
    "BULAN" smallint,
    "TAHUN" character varying(4),
    "JENIS" character varying(20),
    "KETERANGAN" character varying(50),
    "JUMLAH" real
);


ALTER TABLE hris.rpt_jumlah_asn OWNER TO postgres;

--
-- TOC entry 464 (class 1259 OID 36193)
-- Name: rpt_jumlah_asn_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rpt_jumlah_asn_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rpt_jumlah_asn_ID_seq" OWNER TO postgres;

--
-- TOC entry 6267 (class 0 OID 0)
-- Dependencies: 464
-- Name: rpt_jumlah_asn_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rpt_jumlah_asn_ID_seq" OWNED BY hris.rpt_jumlah_asn."ID";


--
-- TOC entry 465 (class 1259 OID 36195)
-- Name: rpt_jumlah_asn_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rpt_jumlah_asn_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rpt_jumlah_asn_id_seq OWNER TO postgres;

--
-- TOC entry 6268 (class 0 OID 0)
-- Dependencies: 465
-- Name: rpt_jumlah_asn_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rpt_jumlah_asn_id_seq OWNED BY hris.rpt_jumlah_asn."ID";


--
-- TOC entry 466 (class 1259 OID 36197)
-- Name: rpt_pendidikan_bulan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rpt_pendidikan_bulan (
    "ID" smallint NOT NULL,
    "TINGKAT_PENDIDIKAN" character varying(16),
    "NAMA_TINGKAT" character varying(255),
    "BULAN" smallint,
    "TAHUN" smallint,
    "JUMLAH" smallint
);


ALTER TABLE hris.rpt_pendidikan_bulan OWNER TO postgres;

--
-- TOC entry 467 (class 1259 OID 36200)
-- Name: rpt_pendidikan_bulan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rpt_pendidikan_bulan_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rpt_pendidikan_bulan_ID_seq" OWNER TO postgres;

--
-- TOC entry 6270 (class 0 OID 0)
-- Dependencies: 467
-- Name: rpt_pendidikan_bulan_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rpt_pendidikan_bulan_ID_seq" OWNED BY hris.rpt_pendidikan_bulan."ID";


--
-- TOC entry 469 (class 1259 OID 36208)
-- Name: rwt_assesmen_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_assesmen_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_assesmen_ID_seq" OWNER TO postgres;

--
-- TOC entry 6271 (class 0 OID 0)
-- Dependencies: 469
-- Name: rwt_assesmen_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rwt_assesmen_ID_seq" OWNED BY hris.rwt_assesmen."ID";


--
-- TOC entry 625 (class 1259 OID 221074)
-- Name: rwt_assesmen_bak; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_assesmen_bak (
    "ID" integer,
    "PNS_ID" character(32),
    "PNS_NIP" character(25),
    "TAHUN" character(4),
    "FILE_UPLOAD" character(100),
    "NILAI" real,
    "NILAI_KINERJA" real,
    "TAHUN_PENILAIAN_ID" character varying(32),
    "TAHUN_PENILAIAN_TITLE" character varying(4),
    "FULLNAME" character varying(255),
    "POSISI_ID" character varying(20),
    "UNIT_ORG_ID" character varying(50),
    "NAMA_UNOR" character varying(200),
    "SARANPENGEMBANGAN" text,
    "FILE_UPLOAD_FB_POTENSI" character varying(255),
    "FILE_UPLOAD_LENGKAP_PT" character varying(255),
    "FILE_UPLOAD_FB_PT" character varying(255),
    "FILE_UPLOAD_EXISTS" smallint
);


ALTER TABLE hris.rwt_assesmen_bak OWNER TO postgres;

--
-- TOC entry 647 (class 1259 OID 263590)
-- Name: rwt_assesmen_id_file_exist_13_09_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_assesmen_id_file_exist_13_09_2020 (
    "ID" integer,
    "FILE_UPLOAD_EXISTS" smallint
);


ALTER TABLE hris.rwt_assesmen_id_file_exist_13_09_2020 OWNER TO postgres;

--
-- TOC entry 848 (class 1259 OID 1844379)
-- Name: rwt_diklat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_diklat (
    id bigint NOT NULL,
    jenis_diklat character varying,
    jenis_diklat_id character varying,
    institusi_penyelenggara character varying,
    nomor_sertifikat character varying,
    tanggal_mulai date,
    tanggal_selesai date,
    tahun_diklat integer,
    durasi_jam integer,
    pns_orang_id character varying,
    nip_baru character varying,
    createddate timestamp without time zone DEFAULT now(),
    diklat_struktural_id character varying,
    nama_diklat character varying,
    file_base64 text,
    rumpun_diklat character varying,
    rumpun_diklat_id character varying,
    sudah_kirim_siasn character varying DEFAULT 'belum'::character varying,
    siasn_id character varying
);


ALTER TABLE hris.rwt_diklat OWNER TO postgres;

--
-- TOC entry 847 (class 1259 OID 1844377)
-- Name: rwt_diklat_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_diklat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_diklat_id_seq OWNER TO postgres;

--
-- TOC entry 6274 (class 0 OID 0)
-- Dependencies: 847
-- Name: rwt_diklat_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_diklat_id_seq OWNED BY hris.rwt_diklat.id;


--
-- TOC entry 470 (class 1259 OID 36210)
-- Name: rwt_hukdis; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_hukdis (
    "ID" bigint NOT NULL,
    "PNS_ID" character(32),
    "PNS_NIP" character(21),
    "NAMA" character(200),
    "ID_GOLONGAN" character(2),
    "NAMA_GOLONGAN" character(20),
    "ID_JENIS_HUKUMAN" character(2),
    "NAMA_JENIS_HUKUMAN" character(100),
    "SK_NOMOR" character(30),
    "SK_TANGGAL" date,
    "TANGGAL_MULAI_HUKUMAN" date,
    "MASA_TAHUN" integer,
    "MASA_BULAN" integer,
    "TANGGAL_AKHIR_HUKUMAN" date,
    "NO_PP" character(20),
    "NO_SK_PEMBATALAN" character(20),
    "TANGGAL_SK_PEMBATALAN" date,
    "ID_BKN" character varying(255),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255)
);


ALTER TABLE hris.rwt_hukdis OWNER TO postgres;

--
-- TOC entry 471 (class 1259 OID 36216)
-- Name: rwt_hukdis_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_hukdis_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_hukdis_ID_seq" OWNER TO postgres;

--
-- TOC entry 6276 (class 0 OID 0)
-- Dependencies: 471
-- Name: rwt_hukdis_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rwt_hukdis_ID_seq" OWNED BY hris.rwt_hukdis."ID";


--
-- TOC entry 286 (class 1259 OID 35227)
-- Name: rwt_jabatan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_jabatan (
    "ID_BKN" character(64),
    "PNS_ID" character(100),
    "PNS_NIP" character(25),
    "PNS_NAMA" character(200),
    "ID_UNOR" character(100),
    "UNOR" text,
    "ID_JENIS_JABATAN" character(10),
    "JENIS_JABATAN" character(250),
    "ID_JABATAN" character(100),
    "NAMA_JABATAN" text,
    "ID_ESELON" character(32),
    "ESELON" character(100),
    "TMT_JABATAN" date,
    "NOMOR_SK" character(100),
    "TANGGAL_SK" date,
    "ID_SATUAN_KERJA" character varying(36),
    "TMT_PELANTIKAN" date,
    "IS_ACTIVE" character(1),
    "ESELON1" text,
    "ESELON2" text,
    "ESELON3" text,
    "ESELON4" text,
    "ID" bigint DEFAULT nextval('hris."rwt_jabatan_ID_seq"'::regclass) NOT NULL,
    "CATATAN" character(255),
    "JENIS_SK" character(100),
    "LAST_UPDATED" date,
    "STATUS_SATKER" integer,
    "STATUS_BIRO" integer,
    "ID_JABATAN_BKN" character varying(36),
    "ID_UNOR_BKN" character varying(36),
    "JABATAN_TERAKHIR" integer,
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255),
    "ID_TABEL_MUTASI" bigint,
    "TERMINATED_DATE" date
);


ALTER TABLE hris.rwt_jabatan OWNER TO postgres;

--
-- TOC entry 472 (class 1259 OID 36218)
-- Name: rwt_kgb; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_kgb (
    pegawai_id integer,
    tmt_sk date,
    alasan character varying(255),
    mv_kgb_id bigint,
    no_sk character varying(255),
    pejabat character varying(255),
    id bigint NOT NULL,
    ref character varying(255) DEFAULT hris.uuid_generate_v4(),
    tgl_sk date,
    pegawai_nama character varying(255),
    pegawai_nip character varying(255),
    birth_place character varying(255),
    birth_date date,
    o_gol_ruang character varying(255),
    o_gol_tmt character varying(255),
    o_masakerja_thn smallint,
    o_masakerja_bln smallint,
    o_gapok character varying(255),
    o_jabatan_text character varying(255),
    o_tmt_jabatan date,
    n_gol_ruang character varying(255),
    n_gol_tmt character varying(255),
    n_masakerja_thn smallint,
    n_masakerja_bln smallint,
    n_gapok character varying(255),
    n_jabatan_text character varying(255),
    n_tmt_jabatan date,
    n_golongan_id integer,
    unit_kerja_text character varying(255),
    unit_kerja_induk_text character varying(255),
    unit_kerja_induk_id character varying(255),
    kantor_pembayaran character varying(255),
    last_education character varying(255),
    last_education_date date,
    nama_pejabat character varying(100),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255)
);


ALTER TABLE hris.rwt_kgb OWNER TO postgres;

--
-- TOC entry 473 (class 1259 OID 36225)
-- Name: rwt_kgb_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_kgb_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_kgb_id_seq OWNER TO postgres;

--
-- TOC entry 6279 (class 0 OID 0)
-- Dependencies: 473
-- Name: rwt_kgb_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_kgb_id_seq OWNED BY hris.rwt_kgb.id;


--
-- TOC entry 733 (class 1259 OID 1512863)
-- Name: rwt_kinerja; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_kinerja (
    id integer NOT NULL,
    id_simarin integer,
    tahun integer,
    periode_mulai date,
    periode_selesai date,
    format_skp character varying(20),
    jenis_skp character varying(20),
    idp_pegawai character varying(7),
    nip character varying(20),
    nama character varying(200),
    panggol character varying(50),
    jabatan character varying(200),
    penugasan character varying(200),
    id_unit_kerja integer,
    unit_kerja character varying(200),
    idp_penilai character varying(7),
    nip_penilai character varying(20),
    nama_penilai character varying(200),
    panggol_penilai character varying(50),
    jabatan_penilai character varying(200),
    penugasan_penilai character varying(200),
    id_unit_kerja_penilai integer,
    unit_kerja_penilai character varying(200),
    idp_atasan_penilai character varying(7),
    nip_atasan_penilai character varying(20),
    nama_atasan_penilai character varying(200),
    panggol_atasan_penilai character varying(50),
    jabatan_atasan_penilai character varying(200),
    penugasan_atasan_penilai character varying(200),
    id_unit_kerja_atasan_penilai integer,
    unit_kerja_atasan_penilai character varying(200),
    idp_penilai_simarin character varying(7),
    nip_penilai_simarin character varying(20),
    nama_penilai_simarin character varying(200),
    panggol_penilai_simarin character varying(50),
    jabatan_penilai_simarin character varying(200),
    penugasan_penilai_simarin character varying(200),
    id_unit_kerja_penilai_simarin integer,
    unit_kerja_penilai_simarin character varying(200),
    idp_penilai_realisasi character varying(7),
    nip_penilai_realisasi character varying(20),
    nama_penilai_realisasi character varying(200),
    panggol_penilai_realisasi character varying(50),
    jabatan_penilai_realisasi character varying(200),
    penugasan_penilai_realisasi character varying(200),
    id_unit_kerja_penilai_realisasi integer,
    unit_kerja_penilai_realisasi character varying(200),
    idp_atasan_penilai_realisasi character varying(7),
    nip_atasan_penilai_realisasi character varying(20),
    nama_atasan_penilai_realisasi character varying(200),
    panggol_atasan_penilai_realisasi character varying(50),
    jabatan_atasan_penilai_realisasi character varying(200),
    penugasan_atasan_penilai_realisasi character varying(200),
    id_unit_kerja_atasan_penilai_realisasi integer,
    unit_kerja_atasan_penilai_realisasi character varying(200),
    idp_penilai_realisasi_simarin character varying(7),
    nip_penilai_realisasi_simarin character varying(20),
    nama_penilai_realisasi_simarin character varying(200),
    panggol_penilai_realisasi_simarin character varying(50),
    jabatan_penilai_realisasi_simarin character varying(200),
    penugasan_penilai_realisasi_simarin character varying(200),
    id_unit_kerja_penilai_realisasi_simarin integer,
    unit_kerja_penilai_realisasi_simarin character varying(200),
    nama_realisasi character varying(200),
    nip_realisasi character varying(20),
    panggol_realisasi character varying(50),
    jabatan_realisasi character varying(200),
    penugasan_realisasi character varying(200),
    id_unit_kerja_realisasi integer,
    unit_kerja_realisasi character varying(200),
    skp_instansi_lama character varying(200),
    capaian_kinerja_org character varying(20),
    pola_distribusi_img character varying(200),
    nilai_akhir_hasil_kerja real,
    rating_hasil_kerja character varying(50),
    nilai_akhir_perilaku_kerja real,
    rating_perilaku_kerja character varying(50),
    predikat_kinerja character varying(100),
    tunjangan_kinerja integer,
    catatan_rekomendasi text,
    is_keberatan character varying(100),
    keberatan text,
    penjelasan_pejabat_penilai text,
    keputusan_rekomendasi_atasan_pejabat text,
    url_skp_instansi_lama character varying(200),
    is_keberatan_date character varying(5),
    ref uuid DEFAULT hris.uuid_generate_v4(),
    id_arsip integer,
    created_date timestamp(6) without time zone DEFAULT now()
);


ALTER TABLE hris.rwt_kinerja OWNER TO postgres;

--
-- TOC entry 732 (class 1259 OID 1512861)
-- Name: rwt_kinerja_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_kinerja_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_kinerja_id_seq OWNER TO postgres;

--
-- TOC entry 6280 (class 0 OID 0)
-- Dependencies: 732
-- Name: rwt_kinerja_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_kinerja_id_seq OWNED BY hris.rwt_kinerja.id;


--
-- TOC entry 474 (class 1259 OID 36227)
-- Name: rwt_kursus; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_kursus (
    "PNS_ID_x" character(32),
    "PNS_NIP" character(30),
    "TIPE_KURSUS" character(10),
    "JENIS_KURSUS" character(30),
    "NAMA_KURSUS" character(200),
    "LAMA_KURSUS" double precision,
    "TANGGAL_KURSUS" date,
    "NO_SERTIFIKAT" character(50),
    "INSTANSI" character(200),
    "INSTITUSI_PENYELENGGARA" character(200),
    "ID" integer NOT NULL,
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(200),
    "CREATEDDATE" timestamp without time zone DEFAULT now(),
    "SIASN_ID" character varying,
    "PNS_ID" character varying
);


ALTER TABLE hris.rwt_kursus OWNER TO postgres;

--
-- TOC entry 475 (class 1259 OID 36233)
-- Name: rwt_kursus_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_kursus_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_kursus_ID_seq" OWNER TO postgres;

--
-- TOC entry 6282 (class 0 OID 0)
-- Dependencies: 475
-- Name: rwt_kursus_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rwt_kursus_ID_seq" OWNED BY hris.rwt_kursus."ID";


--
-- TOC entry 476 (class 1259 OID 36235)
-- Name: rwt_penghargaan_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_penghargaan_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_penghargaan_ID_seq" OWNER TO postgres;

--
-- TOC entry 477 (class 1259 OID 36237)
-- Name: rwt_penghargaan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_penghargaan (
    "ID" integer DEFAULT nextval('hris."rwt_penghargaan_ID_seq"'::regclass) NOT NULL,
    "PNS_ID" character(32),
    "PNS_NIP" character(21),
    "NAMA" character(200),
    "ID_GOLONGAN" character(2),
    "NAMA_GOLONGAN" character(100),
    "ID_JENIS_PENGHARGAAN" character(3),
    "NAMA_JENIS_PENGHARGAAN" character(100),
    "SK_NOMOR" character(30),
    "SK_TANGGAL" date,
    "ID_BKN" character varying(255),
    "SURAT_USUL" text,
    "KETERANGAN" text
);


ALTER TABLE hris.rwt_penghargaan OWNER TO postgres;

--
-- TOC entry 879 (class 1259 OID 2514291)
-- Name: rwt_penghargaan_umum; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_penghargaan_umum (
    id bigint NOT NULL,
    jenis_penghargaan character varying,
    deskripsi_penghargaan character varying,
    tanggal_penghargaan date,
    createddate timestamp without time zone DEFAULT now(),
    exist boolean DEFAULT true
);


ALTER TABLE hris.rwt_penghargaan_umum OWNER TO postgres;

--
-- TOC entry 878 (class 1259 OID 2514289)
-- Name: rwt_penghargaan_umum_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_penghargaan_umum_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_penghargaan_umum_id_seq OWNER TO postgres;

--
-- TOC entry 6284 (class 0 OID 0)
-- Dependencies: 878
-- Name: rwt_penghargaan_umum_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_penghargaan_umum_id_seq OWNED BY hris.rwt_penghargaan_umum.id;


--
-- TOC entry 877 (class 1259 OID 2514269)
-- Name: rwt_penugasan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_penugasan (
    id bigint NOT NULL,
    tipe_jabatan character varying,
    deskripsi_jabatan text,
    tanggal_mulai date,
    tanggal_selesai date,
    createddate timestamp without time zone DEFAULT now(),
    exist boolean DEFAULT true
);


ALTER TABLE hris.rwt_penugasan OWNER TO postgres;

--
-- TOC entry 876 (class 1259 OID 2514267)
-- Name: rwt_penugasan_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_penugasan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_penugasan_id_seq OWNER TO postgres;

--
-- TOC entry 6285 (class 0 OID 0)
-- Dependencies: 876
-- Name: rwt_penugasan_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_penugasan_id_seq OWNED BY hris.rwt_penugasan.id;


--
-- TOC entry 478 (class 1259 OID 36244)
-- Name: rwt_pindah_unit_kerja_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_pindah_unit_kerja_id_seq
    START WITH 2
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_pindah_unit_kerja_id_seq OWNER TO postgres;

--
-- TOC entry 479 (class 1259 OID 36246)
-- Name: rwt_pindah_unit_kerja; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_pindah_unit_kerja (
    "ID" character varying(255) DEFAULT nextval('hris.rwt_pindah_unit_kerja_id_seq'::regclass) NOT NULL,
    "PNS_ID" character varying(255),
    "PNS_NIP" character varying(255),
    "PNS_NAMA" character varying(255),
    "SK_NOMOR" character varying(255),
    "ASAL_ID" character varying(255),
    "ASAL_NAMA" character varying(255),
    "ID_UNOR_BARU" character varying(255),
    "NAMA_UNOR_BARU" character varying(255),
    "ID_INSTANSI" character varying(255),
    "NAMA_INSTANSI" character varying(255),
    "SK_TANGGAL" date,
    "ID_SATUAN_KERJA" character varying(100),
    "NAMA_SATUAN_KERJA" character varying(255),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255)
);


ALTER TABLE hris.rwt_pindah_unit_kerja OWNER TO postgres;

--
-- TOC entry 606 (class 1259 OID 47184)
-- Name: rwt_pns_cpns; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_pns_cpns (
    "ID" integer NOT NULL,
    "STATUS_KEPEGAWAIAN" character varying(5),
    "TMT_CPNS" date,
    "TGL_SK_CPNS" date,
    "NO_SK_CPNS" character varying(100),
    "JENIS_PENGADAAN" character varying(30),
    "TGL_SPMT" date,
    "NO_SPMT" character varying(100),
    "TMT_PNS" date,
    "TGL_SK_PNS" date,
    "N0_SK_PNS" character varying(100),
    "TGL_PERTEK_C2TH" date,
    "NO_PERTEK_C2TH" character varying(100),
    "TGL_KEP_HONORER_2TAHUN" date,
    "NO_PERTEK_KEP_HONORER_2TAHUN" character varying(50),
    "KARIS_KARSU" character varying(20),
    "KARPEG" character varying(20),
    "TGL_STTPL" date,
    "NO_STTPL" character varying(100),
    "TGL_DOKTER" date,
    "NO_SURAT_DOKTER" character varying(50),
    "NAMA_JABATAN_ANGKAT_CPNS" character varying(200),
    "PNS_ID" character varying(32),
    "PNS_NIP" character varying(18)
);


ALTER TABLE hris.rwt_pns_cpns OWNER TO postgres;

--
-- TOC entry 605 (class 1259 OID 47182)
-- Name: rwt_pns_cpns_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_pns_cpns_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_pns_cpns_ID_seq" OWNER TO postgres;

--
-- TOC entry 6288 (class 0 OID 0)
-- Dependencies: 605
-- Name: rwt_pns_cpns_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rwt_pns_cpns_ID_seq" OWNED BY hris.rwt_pns_cpns."ID";


--
-- TOC entry 480 (class 1259 OID 36253)
-- Name: rwt_tugas_belajar; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_tugas_belajar (
    "ID" integer NOT NULL,
    "NAMA" character varying(100),
    "NIP" character varying(30),
    "TINGKAT_PENDIDIKAN" character varying(5),
    "PROGRAM_STUDI" character varying(200),
    "FAKULTAS" character varying(100),
    "UNIVERSITAS" character varying(100),
    "MULAI_BELAJAR" date,
    "AKHIR_BELAJAR" date,
    "NOMOR_SK" character varying(50),
    "TANGGAL_SK" date,
    "KETERANGAN" character varying(200),
    "JENIS_USUL" character varying(20),
    "ID_PENGAJUAN" character varying(20),
    "FILE_BASE64" text,
    "KETERANGAN_BERKAS" character varying(255)
);


ALTER TABLE hris.rwt_tugas_belajar OWNER TO postgres;

--
-- TOC entry 481 (class 1259 OID 36259)
-- Name: rwt_tugas_belajar_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."rwt_tugas_belajar_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."rwt_tugas_belajar_ID_seq" OWNER TO postgres;

--
-- TOC entry 6290 (class 0 OID 0)
-- Dependencies: 481
-- Name: rwt_tugas_belajar_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."rwt_tugas_belajar_ID_seq" OWNED BY hris.rwt_tugas_belajar."ID";


--
-- TOC entry 875 (class 1259 OID 2502450)
-- Name: rwt_ujikom; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.rwt_ujikom (
    id bigint NOT NULL,
    jenis_ujikom character varying,
    nip_baru character varying,
    link_sertifikat character varying,
    createddate timestamp without time zone DEFAULT now(),
    exist boolean,
    tahun integer
);


ALTER TABLE hris.rwt_ujikom OWNER TO postgres;

--
-- TOC entry 874 (class 1259 OID 2502448)
-- Name: rwt_ujikom_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.rwt_ujikom_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.rwt_ujikom_id_seq OWNER TO postgres;

--
-- TOC entry 6291 (class 0 OID 0)
-- Dependencies: 874
-- Name: rwt_ujikom_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.rwt_ujikom_id_seq OWNED BY hris.rwt_ujikom.id;


--
-- TOC entry 482 (class 1259 OID 36261)
-- Name: schema_version; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.schema_version (
    type character varying(40) NOT NULL,
    version integer DEFAULT 0 NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE hris.schema_version OWNER TO postgres;

--
-- TOC entry 483 (class 1259 OID 36265)
-- Name: schema_version_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.schema_version_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.schema_version_id_seq OWNER TO postgres;

--
-- TOC entry 6293 (class 0 OID 0)
-- Dependencies: 483
-- Name: schema_version_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.schema_version_id_seq OWNED BY hris.schema_version.id;


--
-- TOC entry 484 (class 1259 OID 36267)
-- Name: semen_bpcb_sulsel; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.semen_bpcb_sulsel (
    nip character varying(30) NOT NULL,
    nama character varying(50)
);


ALTER TABLE hris.semen_bpcb_sulsel OWNER TO postgres;

--
-- TOC entry 485 (class 1259 OID 36270)
-- Name: settings; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.settings (
    name text NOT NULL,
    module character varying(50) NOT NULL,
    value character varying(500) NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE hris.settings OWNER TO postgres;

--
-- TOC entry 486 (class 1259 OID 36276)
-- Name: settings_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.settings_id_seq OWNER TO postgres;

--
-- TOC entry 6296 (class 0 OID 0)
-- Dependencies: 486
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.settings_id_seq OWNED BY hris.settings.id;


--
-- TOC entry 614 (class 1259 OID 47739)
-- Name: sisa_cuti; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.sisa_cuti (
    "ID" integer NOT NULL,
    "PNS_NIP" character varying(18) NOT NULL,
    "TAHUN" character varying(4) NOT NULL,
    "SISA_N" smallint,
    "SISA_N_1" smallint,
    "SISA_N_2" smallint,
    "SISA" smallint,
    "NAMA" character varying(100),
    "SUDAH_DIAMBIL" smallint
);


ALTER TABLE hris.sisa_cuti OWNER TO postgres;

--
-- TOC entry 610 (class 1259 OID 47713)
-- Name: sisa_cuti_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."sisa_cuti_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."sisa_cuti_ID_seq" OWNER TO postgres;

--
-- TOC entry 6298 (class 0 OID 0)
-- Dependencies: 610
-- Name: sisa_cuti_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."sisa_cuti_ID_seq" OWNED BY hris.sisa_cuti."ID";


--
-- TOC entry 725 (class 1259 OID 1161007)
-- Name: synch_jumlah_pegawai; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.synch_jumlah_pegawai (
    id bigint NOT NULL,
    kode_unit_kerja character varying(20),
    id_unor_bkn character varying(32),
    nama_eselon_1 character varying(200),
    satker character varying(200),
    satker_singkatan character varying(100),
    jumlah_mutasi integer,
    jumlah_dikbudhr integer,
    update_time timestamp(6) without time zone
);


ALTER TABLE hris.synch_jumlah_pegawai OWNER TO postgres;

--
-- TOC entry 724 (class 1259 OID 1161005)
-- Name: synch_jumlah_pegawai_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.synch_jumlah_pegawai_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.synch_jumlah_pegawai_id_seq OWNER TO postgres;

--
-- TOC entry 6299 (class 0 OID 0)
-- Dependencies: 724
-- Name: synch_jumlah_pegawai_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.synch_jumlah_pegawai_id_seq OWNED BY hris.synch_jumlah_pegawai.id;


--
-- TOC entry 678 (class 1259 OID 347514)
-- Name: tb_nomor_batasan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tb_nomor_batasan (
    "BATAS_AWAL" character varying(100) NOT NULL,
    "BATAS_AKHIR" character varying(100) NOT NULL,
    "TAHUN_NOMOR" character varying(100)
);


ALTER TABLE hris.tb_nomor_batasan OWNER TO postgres;

--
-- TOC entry 679 (class 1259 OID 347517)
-- Name: tb_nomor_surat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tb_nomor_surat (
    id double precision NOT NULL,
    nomor_surat double precision NOT NULL,
    kode character varying(100) NOT NULL,
    tanggal date NOT NULL,
    kepada text NOT NULL,
    keterangan text NOT NULL,
    username character varying(100) NOT NULL
);


ALTER TABLE hris.tb_nomor_surat OWNER TO postgres;

--
-- TOC entry 681 (class 1259 OID 394773)
-- Name: tbl_NUMPANG1; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris."tbl_NUMPANG1" (
    "ID_FILE" character varying(255)
);


ALTER TABLE hris."tbl_NUMPANG1" OWNER TO postgres;

--
-- TOC entry 683 (class 1259 OID 418901)
-- Name: tbl_NUMPANG2; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris."tbl_NUMPANG2" (
    "ID_FILE" character varying(255)
);


ALTER TABLE hris."tbl_NUMPANG2" OWNER TO postgres;

--
-- TOC entry 618 (class 1259 OID 102085)
-- Name: tbl_cek; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_cek (
    id_file character varying(200)
);


ALTER TABLE hris.tbl_cek OWNER TO postgres;

--
-- TOC entry 619 (class 1259 OID 102091)
-- Name: tbl_cek_2; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_cek_2 (
    id_file character varying(200)
);


ALTER TABLE hris.tbl_cek_2 OWNER TO postgres;

--
-- TOC entry 488 (class 1259 OID 36287)
-- Name: tbl_file_ds_corrector; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_corrector (
    korektor_ke smallint,
    id_pegawai_korektor character varying(100),
    is_returned smallint,
    catatan_koreksi text,
    is_corrected smallint,
    id_file character varying(200),
    id integer NOT NULL
);


ALTER TABLE hris.tbl_file_ds_corrector OWNER TO postgres;

--
-- TOC entry 6306 (class 0 OID 0)
-- Dependencies: 488
-- Name: COLUMN tbl_file_ds_corrector.is_returned; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector.is_returned IS '1=dikembalikan, 0/null = sudah oke';


--
-- TOC entry 6307 (class 0 OID 0)
-- Dependencies: 488
-- Name: COLUMN tbl_file_ds_corrector.is_corrected; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector.is_corrected IS '1=koreksi ok, 2=siap koreksi, 0/null = masih antrian';


--
-- TOC entry 489 (class 1259 OID 36293)
-- Name: tbl_file_ds_corrector_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tbl_file_ds_corrector_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tbl_file_ds_corrector_id_seq OWNER TO postgres;

--
-- TOC entry 6309 (class 0 OID 0)
-- Dependencies: 489
-- Name: tbl_file_ds_corrector_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tbl_file_ds_corrector_id_seq OWNED BY hris.tbl_file_ds_corrector.id;


--
-- TOC entry 721 (class 1259 OID 1001311)
-- Name: tbl_file_ds_corrector_backup_1; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_corrector_backup_1 (
    korektor_ke smallint,
    id_pegawai_korektor character varying(100),
    is_returned smallint,
    catatan_koreksi text,
    is_corrected smallint,
    id_file character varying(200),
    id integer DEFAULT nextval('hris.tbl_file_ds_corrector_id_seq'::regclass) NOT NULL
);


ALTER TABLE hris.tbl_file_ds_corrector_backup_1 OWNER TO postgres;

--
-- TOC entry 6311 (class 0 OID 0)
-- Dependencies: 721
-- Name: COLUMN tbl_file_ds_corrector_backup_1.is_returned; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector_backup_1.is_returned IS '1=dikembalikan, 0/null = sudah oke';


--
-- TOC entry 6312 (class 0 OID 0)
-- Dependencies: 721
-- Name: COLUMN tbl_file_ds_corrector_backup_1.is_corrected; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector_backup_1.is_corrected IS '1=koreksi ok, 2=siap koreksi, 0/null = masih antrian';


--
-- TOC entry 722 (class 1259 OID 1001320)
-- Name: tbl_file_ds_corrector_backup_2; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_corrector_backup_2 (
    korektor_ke smallint,
    id_pegawai_korektor character varying(100),
    is_returned smallint,
    catatan_koreksi text,
    is_corrected smallint,
    id_file character varying(200),
    id integer DEFAULT nextval('hris.tbl_file_ds_corrector_id_seq'::regclass) NOT NULL
);


ALTER TABLE hris.tbl_file_ds_corrector_backup_2 OWNER TO postgres;

--
-- TOC entry 6314 (class 0 OID 0)
-- Dependencies: 722
-- Name: COLUMN tbl_file_ds_corrector_backup_2.is_returned; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector_backup_2.is_returned IS '1=dikembalikan, 0/null = sudah oke';


--
-- TOC entry 6315 (class 0 OID 0)
-- Dependencies: 722
-- Name: COLUMN tbl_file_ds_corrector_backup_2.is_corrected; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tbl_file_ds_corrector_backup_2.is_corrected IS '1=koreksi ok, 2=siap koreksi, 0/null = masih antrian';


--
-- TOC entry 490 (class 1259 OID 36302)
-- Name: tbl_file_ds_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tbl_file_ds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tbl_file_ds_id_seq OWNER TO postgres;

--
-- TOC entry 6317 (class 0 OID 0)
-- Dependencies: 490
-- Name: tbl_file_ds_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tbl_file_ds_id_seq OWNED BY hris.tbl_file_ds.id;


--
-- TOC entry 856 (class 1259 OID 2162388)
-- Name: tbl_file_ds_khusus_login; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_khusus_login (
    "ID_FILE" character varying(255) NOT NULL
);


ALTER TABLE hris.tbl_file_ds_khusus_login OWNER TO postgres;

--
-- TOC entry 616 (class 1259 OID 63899)
-- Name: tbl_file_ds_riwayat; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_riwayat (
    id_file character varying(200),
    id_pemroses character varying(255),
    tindakan text,
    catatan_tindakan text,
    waktu_tindakan timestamp(6) without time zone,
    akses_pengguna character varying(200),
    id_riwayat bigint NOT NULL
);


ALTER TABLE hris.tbl_file_ds_riwayat OWNER TO postgres;

--
-- TOC entry 617 (class 1259 OID 63955)
-- Name: tbl_file_ds_riwayat_id_riwayat_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tbl_file_ds_riwayat_id_riwayat_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tbl_file_ds_riwayat_id_riwayat_seq OWNER TO postgres;

--
-- TOC entry 6320 (class 0 OID 0)
-- Dependencies: 617
-- Name: tbl_file_ds_riwayat_id_riwayat_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tbl_file_ds_riwayat_id_riwayat_seq OWNED BY hris.tbl_file_ds_riwayat.id_riwayat;


--
-- TOC entry 703 (class 1259 OID 534504)
-- Name: tbl_file_ds_riwayat_backup_1; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ds_riwayat_backup_1 (
    id_file character varying(200),
    id_pemroses character varying(255),
    tindakan text,
    catatan_tindakan text,
    waktu_tindakan timestamp(6) without time zone,
    akses_pengguna character varying(200),
    id_riwayat bigint DEFAULT nextval('hris.tbl_file_ds_riwayat_id_riwayat_seq'::regclass) NOT NULL,
    id bigint
);


ALTER TABLE hris.tbl_file_ds_riwayat_backup_1 OWNER TO postgres;

--
-- TOC entry 491 (class 1259 OID 36304)
-- Name: tbl_file_ttd; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_file_ttd (
    id_pns_bkn character varying(200) NOT NULL,
    nip character varying(50),
    base64ttd text
);


ALTER TABLE hris.tbl_file_ttd OWNER TO postgres;

--
-- TOC entry 615 (class 1259 OID 52222)
-- Name: tbl_kategori_dokumen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_kategori_dokumen (
    id_kategori smallint NOT NULL,
    kategori_dokumen character varying(255),
    update_jabatan smallint,
    via_ds smallint,
    kelompok character varying(255),
    kaitan character varying(255),
    grup_proses character varying(255),
    izinkan_kolektif character varying(10),
    grup_info character varying(200),
    untuk_pegawai smallint,
    login_untuk_lihat smallint
);


ALTER TABLE hris.tbl_kategori_dokumen OWNER TO postgres;

--
-- TOC entry 690 (class 1259 OID 443105)
-- Name: tbl_kategori_dokumen_penandatangan; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_kategori_dokumen_penandatangan (
    "ID_URUT" bigint NOT NULL,
    "KELOMPOK" character varying(255),
    "PENANDATANGAN" character varying(255),
    "KOREKTOR_KE" smallint,
    "NAMA_KOREKTOR" character varying(255),
    "JABATAN" text,
    "SATKER" text,
    "ID_PNS" character varying(255),
    "ID_UNOR" character varying(255)
);


ALTER TABLE hris.tbl_kategori_dokumen_penandatangan OWNER TO postgres;

--
-- TOC entry 662 (class 1259 OID 310368)
-- Name: tbl_pengantar_dokumen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tbl_pengantar_dokumen (
    id_pengantar character varying(255) NOT NULL,
    html_lampiran text,
    skema character varying(10)
);


ALTER TABLE hris.tbl_pengantar_dokumen OWNER TO postgres;

--
-- TOC entry 640 (class 1259 OID 249399)
-- Name: tte_master_variable; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_master_variable (
    id smallint NOT NULL,
    label_variable character varying(50),
    nama_variable character varying(50),
    tipe character varying(50),
    keterangan character varying(255)
);


ALTER TABLE hris.tte_master_variable OWNER TO postgres;

--
-- TOC entry 629 (class 1259 OID 249358)
-- Name: tte_ master_variable_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."tte_ master_variable_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."tte_ master_variable_id_seq" OWNER TO postgres;

--
-- TOC entry 6327 (class 0 OID 0)
-- Dependencies: 629
-- Name: tte_ master_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."tte_ master_variable_id_seq" OWNED BY hris.tte_master_variable.id;


--
-- TOC entry 637 (class 1259 OID 249378)
-- Name: tte_master_korektor; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_master_korektor (
    id smallint NOT NULL,
    id_tte_master_proses smallint,
    id_pegawai_korektor character varying(32),
    korektor_ke smallint
);


ALTER TABLE hris.tte_master_korektor OWNER TO postgres;

--
-- TOC entry 630 (class 1259 OID 249360)
-- Name: tte_master_korektor_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_master_korektor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_master_korektor_id_seq OWNER TO postgres;

--
-- TOC entry 6329 (class 0 OID 0)
-- Dependencies: 630
-- Name: tte_master_korektor_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_master_korektor_id_seq OWNED BY hris.tte_master_korektor.id;


--
-- TOC entry 638 (class 1259 OID 249384)
-- Name: tte_master_proses; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_master_proses (
    id integer NOT NULL,
    nama_proses character varying(100) NOT NULL,
    template_sk character varying(100),
    penandatangan_sk character varying(32),
    keterangan_proses text
);


ALTER TABLE hris.tte_master_proses OWNER TO postgres;

--
-- TOC entry 631 (class 1259 OID 249362)
-- Name: tte_master_proses_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_master_proses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_master_proses_id_seq OWNER TO postgres;

--
-- TOC entry 6331 (class 0 OID 0)
-- Dependencies: 631
-- Name: tte_master_proses_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_master_proses_id_seq OWNED BY hris.tte_master_proses.id;


--
-- TOC entry 639 (class 1259 OID 249393)
-- Name: tte_master_proses_variable; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_master_proses_variable (
    id smallint NOT NULL,
    id_proses smallint,
    id_variable smallint
);


ALTER TABLE hris.tte_master_proses_variable OWNER TO postgres;

--
-- TOC entry 632 (class 1259 OID 249364)
-- Name: tte_master_proses_variable_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_master_proses_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_master_proses_variable_id_seq OWNER TO postgres;

--
-- TOC entry 6333 (class 0 OID 0)
-- Dependencies: 632
-- Name: tte_master_proses_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_master_proses_variable_id_seq OWNED BY hris.tte_master_proses_variable.id;


--
-- TOC entry 641 (class 1259 OID 249405)
-- Name: tte_trx_draft_sk; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_trx_draft_sk (
    id bigint NOT NULL,
    id_master_proses smallint,
    nip_sk character varying(32),
    penandatangan_sk character varying(32),
    tgl_sk date,
    nomor_sk character varying(100),
    file_template character varying(100),
    base64pdf_hasil text,
    created_date date,
    created_by bigint,
    updated_date date,
    updated_by bigint,
    id_file character varying(40),
    tmt_sk date,
    nama_pemilik_sk character varying(255),
    halaman_ttd smallint DEFAULT 1,
    show_qrcode smallint DEFAULT 0,
    letak_ttd smallint DEFAULT 0
);


ALTER TABLE hris.tte_trx_draft_sk OWNER TO postgres;

--
-- TOC entry 6334 (class 0 OID 0)
-- Dependencies: 641
-- Name: COLUMN tte_trx_draft_sk.nama_pemilik_sk; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON COLUMN hris.tte_trx_draft_sk.nama_pemilik_sk IS 'nama pemilik sk';


--
-- TOC entry 642 (class 1259 OID 249414)
-- Name: tte_trx_draft_sk_detil; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_trx_draft_sk_detil (
    id integer NOT NULL,
    id_tte_trx_draft_sk bigint,
    id_variable smallint,
    isi character varying(255)
);


ALTER TABLE hris.tte_trx_draft_sk_detil OWNER TO postgres;

--
-- TOC entry 633 (class 1259 OID 249366)
-- Name: tte_trx_draft_sk_detil_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_trx_draft_sk_detil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_trx_draft_sk_detil_id_seq OWNER TO postgres;

--
-- TOC entry 6337 (class 0 OID 0)
-- Dependencies: 633
-- Name: tte_trx_draft_sk_detil_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_trx_draft_sk_detil_id_seq OWNED BY hris.tte_trx_draft_sk_detil.id;


--
-- TOC entry 634 (class 1259 OID 249368)
-- Name: tte_trx_draft_sk_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_trx_draft_sk_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_trx_draft_sk_id_seq OWNER TO postgres;

--
-- TOC entry 6338 (class 0 OID 0)
-- Dependencies: 634
-- Name: tte_trx_draft_sk_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_trx_draft_sk_id_seq OWNED BY hris.tte_trx_draft_sk.id;


--
-- TOC entry 643 (class 1259 OID 249420)
-- Name: tte_trx_korektor_draft; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.tte_trx_korektor_draft (
    id integer NOT NULL,
    id_tte_trx_draft_sk bigint,
    id_pegawai_korektor character varying(32),
    korektor_ke smallint
);


ALTER TABLE hris.tte_trx_korektor_draft OWNER TO postgres;

--
-- TOC entry 635 (class 1259 OID 249370)
-- Name: tte_trx_korektor_draft_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.tte_trx_korektor_draft_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.tte_trx_korektor_draft_id_seq OWNER TO postgres;

--
-- TOC entry 6340 (class 0 OID 0)
-- Dependencies: 635
-- Name: tte_trx_korektor_draft_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.tte_trx_korektor_draft_id_seq OWNED BY hris.tte_trx_korektor_draft.id;


--
-- TOC entry 626 (class 1259 OID 241453)
-- Name: unitkerja_01_sept_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_01_sept_2020 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32)
);


ALTER TABLE hris.unitkerja_01_sept_2020 OWNER TO postgres;

--
-- TOC entry 492 (class 1259 OID 36313)
-- Name: unitkerja_04_04_2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_04_04_2019 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255)
);


ALTER TABLE hris.unitkerja_04_04_2019 OWNER TO postgres;

--
-- TOC entry 493 (class 1259 OID 36319)
-- Name: unitkerja_10_feb_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_10_feb_2020 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32)
);


ALTER TABLE hris.unitkerja_10_feb_2020 OWNER TO postgres;

--
-- TOC entry 494 (class 1259 OID 36325)
-- Name: unitkerja_11_04_2019; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_11_04_2019 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255)
);


ALTER TABLE hris.unitkerja_11_04_2019 OWNER TO postgres;

--
-- TOC entry 730 (class 1259 OID 1365005)
-- Name: unitkerja_20230310; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_20230310 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32),
    "WAKTU" character varying(4),
    "PERATURAN" character varying(100)
);


ALTER TABLE hris.unitkerja_20230310 OWNER TO postgres;

--
-- TOC entry 620 (class 1259 OID 102714)
-- Name: unitkerja_27_juli_2020; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_27_juli_2020 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32)
);


ALTER TABLE hris.unitkerja_27_juli_2020 OWNER TO postgres;

--
-- TOC entry 495 (class 1259 OID 36331)
-- Name: unitkerja_bak; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_bak (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255),
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255)
);


ALTER TABLE hris.unitkerja_bak OWNER TO postgres;

--
-- TOC entry 660 (class 1259 OID 302126)
-- Name: unitkerja_copy1; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.unitkerja_copy1 (
    "NO" character varying(255),
    "KODE_INTERNAL" character varying(255),
    "ID" character varying(255) NOT NULL,
    "NAMA_UNOR" character varying(255),
    "ESELON_ID" character varying(255),
    "CEPAT_KODE" character varying(255),
    "NAMA_JABATAN" character varying(255),
    "NAMA_PEJABAT" character varying(255),
    "DIATASAN_ID" character varying(255),
    "INSTANSI_ID" character varying(255),
    "PEMIMPIN_NON_PNS_ID" character varying(255),
    "PEMIMPIN_PNS_ID" character varying(255),
    "JENIS_UNOR_ID" character varying(255),
    "UNOR_INDUK" character varying(255),
    "JUMLAH_IDEAL_STAFF" character varying(255),
    "ORDER" bigint,
    deleted smallint,
    "IS_SATKER" smallint DEFAULT 0 NOT NULL,
    "ESELON_1" character varying(32),
    "ESELON_2" character varying(32),
    "ESELON_3" character varying(32),
    "ESELON_4" character varying(32),
    "EXPIRED_DATE" date,
    "KETERANGAN" character varying(255),
    "JENIS_SATKER" character varying(255),
    "ABBREVIATION" character varying(255),
    "UNOR_INDUK_PENYETARAAN" character varying(255),
    "JABATAN_ID" character varying(32)
);


ALTER TABLE hris.unitkerja_copy1 OWNER TO postgres;

--
-- TOC entry 496 (class 1259 OID 36337)
-- Name: unitkerja_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.unitkerja_id_seq
    START WITH 1245
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.unitkerja_id_seq OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 35021)
-- Name: unitkerja_old_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.unitkerja_old_id_seq
    START WITH 1243
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.unitkerja_old_id_seq OWNER TO postgres;

--
-- TOC entry 497 (class 1259 OID 36339)
-- Name: update_mandiri; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.update_mandiri (
    "ID" integer NOT NULL,
    "PNS_ID" character(32),
    "KOLOM" character(70),
    "DARI" character(400),
    "PERUBAHAN" character(400),
    "STATUS" integer,
    "VERIFIKASI_BY" integer,
    "VERIFIKASI_TGL" date,
    "UPDATE_TGL" date,
    "NAMA_KOLOM" character(100),
    "LEVEL_UPDATE" integer,
    "ID_TABEL" integer,
    "UPDATED_BY" integer
);


ALTER TABLE hris.update_mandiri OWNER TO postgres;

--
-- TOC entry 498 (class 1259 OID 36345)
-- Name: update_mandiri_ID_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris."update_mandiri_ID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris."update_mandiri_ID_seq" OWNER TO postgres;

--
-- TOC entry 6349 (class 0 OID 0)
-- Dependencies: 498
-- Name: update_mandiri_ID_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris."update_mandiri_ID_seq" OWNED BY hris.update_mandiri."ID";


--
-- TOC entry 499 (class 1259 OID 36347)
-- Name: user_cookies; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.user_cookies (
    user_id bigint NOT NULL,
    token character varying(128) NOT NULL,
    created_on date NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE hris.user_cookies OWNER TO postgres;

--
-- TOC entry 500 (class 1259 OID 36350)
-- Name: user_cookies_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.user_cookies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.user_cookies_id_seq OWNER TO postgres;

--
-- TOC entry 6351 (class 0 OID 0)
-- Dependencies: 500
-- Name: user_cookies_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.user_cookies_id_seq OWNED BY hris.user_cookies.id;


--
-- TOC entry 501 (class 1259 OID 36352)
-- Name: user_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.user_id_seq
    START WITH 32952
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.user_id_seq OWNER TO postgres;

--
-- TOC entry 502 (class 1259 OID 36354)
-- Name: user_meta_meta_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.user_meta_meta_id_seq
    START WITH 414
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.user_meta_meta_id_seq OWNER TO postgres;

--
-- TOC entry 503 (class 1259 OID 36356)
-- Name: user_meta; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.user_meta (
    meta_id integer DEFAULT nextval('hris.user_meta_meta_id_seq'::regclass) NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    meta_key character(255) DEFAULT ''::bpchar NOT NULL,
    meta_value text
);


ALTER TABLE hris.user_meta OWNER TO postgres;

--
-- TOC entry 504 (class 1259 OID 36365)
-- Name: users; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.users (
    id bigint DEFAULT nextval('hris.user_id_seq'::regclass) NOT NULL,
    role_id bigint DEFAULT (4)::bigint NOT NULL,
    email character varying(255) NOT NULL,
    username character varying(255) DEFAULT ''::bpchar NOT NULL,
    password_hash character varying(255) NOT NULL,
    reset_hash character varying(255) DEFAULT NULL::bpchar,
    last_login date,
    last_ip character(40) DEFAULT ''::bpchar NOT NULL,
    created_on date,
    deleted integer DEFAULT 0 NOT NULL,
    reset_by integer,
    banned integer DEFAULT 0 NOT NULL,
    ban_message character(255) DEFAULT NULL::bpchar,
    display_name character(255) DEFAULT ''::bpchar,
    display_name_changed date,
    timezone character(4) DEFAULT 'UM7'::bpchar NOT NULL,
    language character(20) DEFAULT 'english'::bpchar NOT NULL,
    active integer DEFAULT 0 NOT NULL,
    activate_hash character(40) DEFAULT ''::bpchar NOT NULL,
    password_iterations integer NOT NULL,
    force_password_reset integer DEFAULT 0 NOT NULL,
    nip character varying(20) DEFAULT NULL::bpchar,
    satkers text,
    admin_nomor smallint,
    imei character varying(100),
    token character varying(255),
    real_imei character varying(100),
    fcm character varying(255),
    banned_asigo integer DEFAULT 0
);


ALTER TABLE hris.users OWNER TO postgres;

--
-- TOC entry 505 (class 1259 OID 36386)
-- Name: usulan_dokumen; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.usulan_dokumen (
    id bigint NOT NULL,
    perkiraan_id bigint,
    title character varying(255),
    file_upload character varying(255),
    _created_at timestamp(6) without time zone DEFAULT now(),
    _created_by bigint,
    tipe character varying(255)
);


ALTER TABLE hris.usulan_dokumen OWNER TO postgres;

--
-- TOC entry 506 (class 1259 OID 36393)
-- Name: usulan_documents_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.usulan_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.usulan_documents_id_seq OWNER TO postgres;

--
-- TOC entry 6355 (class 0 OID 0)
-- Dependencies: 506
-- Name: usulan_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.usulan_documents_id_seq OWNED BY hris.usulan_dokumen.id;


--
-- TOC entry 659 (class 1259 OID 301935)
-- Name: v_kategori_ds; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.v_kategori_ds AS
 SELECT DISTINCT tbl_file_ds.kategori AS kategori_ds
   FROM hris.tbl_file_ds
  ORDER BY tbl_file_ds.kategori;


ALTER TABLE hris.v_kategori_ds OWNER TO postgres;

--
-- TOC entry 746 (class 1259 OID 1777604)
-- Name: vw_rekap_input_diklat; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_rekap_input_diklat AS
 SELECT 'Diklat Fungsional'::text AS tipe,
    pegawai."NIP_BARU",
        CASE
            WHEN ((r_dik_fung."JUMLAH_JAM")::text = ''::text) THEN '0'::character varying
            ELSE r_dik_fung."JUMLAH_JAM"
        END AS "JUMLAH_JAM",
        CASE
            WHEN (split_part((r_dik_fung."TAHUN")::text, '-'::text, 1) ~ '^\d+$'::text) THEN (split_part((r_dik_fung."TAHUN")::text, '-'::text, 1))::integer
            ELSE 0
        END AS tahun,
    vw."NAMA_SATKER",
    pegawai."IS_DOSEN",
    vw."ID_SATKER"
   FROM ((hris.rwt_diklat_fungsional r_dik_fung
     JOIN hris.pegawai pegawai ON (((pegawai."NIP_BARU")::text = (r_dik_fung."NIP_BARU")::text)))
     JOIN hris.vw_unor_satker vw ON (((pegawai."UNOR_ID")::text = (vw."ID_UNOR")::text)))
UNION ALL
 SELECT 'Diklat Struktural'::text AS tipe,
    pegawai."NIP_BARU",
    '20'::character varying AS "JUMLAH_JAM",
    (r_dik_struk."TAHUN")::integer AS tahun,
    vw."NAMA_SATKER",
    pegawai."IS_DOSEN",
    vw."ID_SATKER"
   FROM ((hris.rwt_diklat_struktural r_dik_struk
     JOIN hris.pegawai pegawai ON (((pegawai."NIP_BARU")::text = (r_dik_struk."PNS_NIP")::text)))
     JOIN hris.vw_unor_satker vw ON (((pegawai."UNOR_ID")::text = (vw."ID_UNOR")::text)))
UNION ALL
 SELECT 'Riwayat Kursus'::text AS tipe,
    pegawai."NIP_BARU",
    (r_kurs."LAMA_KURSUS")::character varying AS "JUMLAH_JAM",
    (date_part('year'::text, r_kurs."TANGGAL_KURSUS"))::integer AS tahun,
    vw."NAMA_SATKER",
    pegawai."IS_DOSEN",
    vw."ID_SATKER"
   FROM ((hris.rwt_kursus r_kurs
     JOIN hris.pegawai pegawai ON (((pegawai."NIP_BARU")::bpchar = r_kurs."PNS_NIP")))
     JOIN hris.vw_unor_satker vw ON (((pegawai."UNOR_ID")::text = (vw."ID_UNOR")::text)))
UNION ALL
 SELECT jd.jenis_diklat AS tipe,
    (r_dik.nip_baru)::character varying(18) AS "NIP_BARU",
    (r_dik.durasi_jam)::character varying AS "JUMLAH_JAM",
    r_dik.tahun_diklat AS tahun,
    vw."NAMA_SATKER",
    pegawai."IS_DOSEN",
    vw."ID_SATKER"
   FROM (((hris.rwt_diklat r_dik
     JOIN hris.pegawai pegawai ON (((pegawai."NIP_BARU")::text = (r_dik.nip_baru)::text)))
     JOIN hris.vw_unor_satker vw ON (((pegawai."UNOR_ID")::text = (vw."ID_UNOR")::text)))
     JOIN hris.jenis_diklat jd ON (((r_dik.jenis_diklat_id)::integer = jd.id)));


ALTER TABLE hris.vw_rekap_input_diklat OWNER TO postgres;

--
-- TOC entry 822 (class 1259 OID 1778163)
-- Name: vw_rekap_pegawai_per_satker; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_rekap_pegawai_per_satker AS
 SELECT satker."ID_SATKER",
    satker."NAMA_SATKER",
    count(p."ID") AS total_pegawai
   FROM ((hris.vw_unor_satker satker
     LEFT JOIN hris.pegawai p ON (((satker."ID_UNOR")::text = (p."UNOR_ID")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pa."ID" = p."ID")))
  WHERE (pa."ID" IS NOT NULL)
  GROUP BY satker."ID_SATKER", satker."NAMA_SATKER"
  ORDER BY satker."NAMA_SATKER" DESC;


ALTER TABLE hris.vw_rekap_pegawai_per_satker OWNER TO postgres;

--
-- TOC entry 6357 (class 0 OID 0)
-- Dependencies: 822
-- Name: VIEW vw_rekap_pegawai_per_satker; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON VIEW hris.vw_rekap_pegawai_per_satker IS 'rekap pegawai aktif per satker';


--
-- TOC entry 823 (class 1259 OID 1778168)
-- Name: vw_biro_sdm_award; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_biro_sdm_award AS
 SELECT count(DISTINCT vw_diklat."NIP_BARU") AS total_pegawai_ngisi_diklat,
    vw_diklat."NAMA_SATKER",
    max(vw_rekap.total_pegawai) AS jumlah_pegawai_satker,
    round((((count(DISTINCT vw_diklat."NIP_BARU"))::numeric / max((vw_rekap.total_pegawai)::numeric)) * (100)::numeric)) AS percentage
   FROM (hris.vw_rekap_pegawai_per_satker vw_rekap
     LEFT JOIN hris.vw_rekap_input_diklat vw_diklat ON (((vw_rekap."ID_SATKER")::text = (vw_diklat."ID_SATKER")::text)))
  WHERE (vw_diklat.tahun = 2022)
  GROUP BY vw_diklat."NAMA_SATKER"
  ORDER BY vw_diklat."NAMA_SATKER";


ALTER TABLE hris.vw_biro_sdm_award OWNER TO postgres;

--
-- TOC entry 734 (class 1259 OID 1528527)
-- Name: vw_cuti_tahunan; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_cuti_tahunan AS
 SELECT i."NIP_PNS",
    i."TAHUN",
    sum(i."JUMLAH") AS jumlah_hari
   FROM (hris.izin i
     LEFT JOIN hris.jenis_izin j ON ((i."KODE_IZIN" = j."ID")))
  WHERE ((i."KODE_IZIN" = 1) AND (i."STATUS_PENGAJUAN" = 3))
  GROUP BY i."KODE_IZIN", i."NIP_PNS", i."TAHUN";


ALTER TABLE hris.vw_cuti_tahunan OWNER TO postgres;

--
-- TOC entry 836 (class 1259 OID 1779058)
-- Name: vw_daftar_riwayat_jabatan; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_daftar_riwayat_jabatan AS
 SELECT row_number() OVER (PARTITION BY rjab."PNS_ID" ORDER BY rjab."TMT_JABATAN" DESC) AS _order,
    rjab."ID_BKN",
    rjab."PNS_ID",
    rjab."PNS_NIP",
    rjab."PNS_NAMA",
    rjab."ID_UNOR",
    rjab."UNOR",
    rjab."ID_JENIS_JABATAN",
    rjab."JENIS_JABATAN",
    rjab."ID_JABATAN",
    rjab."NAMA_JABATAN",
    rjab."ID_ESELON",
    rjab."ESELON",
    rjab."TMT_JABATAN",
    rjab."NOMOR_SK",
    rjab."TANGGAL_SK",
    rjab."ID_SATUAN_KERJA",
    rjab."TMT_PELANTIKAN",
    rjab."IS_ACTIVE",
    rjab."ESELON1",
    rjab."ESELON2",
    rjab."ESELON3",
    rjab."ESELON4",
    rjab."ID",
    rjab."CATATAN",
    rjab."JENIS_SK",
    rjab."LAST_UPDATED",
    rjab."STATUS_SATKER",
    rjab."STATUS_BIRO",
    rjab."ID_JABATAN_BKN",
    rjab."ID_UNOR_BKN",
    rjab."JABATAN_TERAKHIR"
   FROM hris.rwt_jabatan_empty rjab
  WHERE (rjab."TMT_JABATAN" IS NOT NULL);


ALTER TABLE hris.vw_daftar_riwayat_jabatan OWNER TO postgres;

--
-- TOC entry 824 (class 1259 OID 1778173)
-- Name: vw_drh; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_drh AS
 SELECT pegawai."PNS_ID",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."GOL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."JABATAN_ID",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."TAHUN_LULUS",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."PHOTO",
    pegawai."LOKASI_KERJA",
    pegawai."TEMPAT_LAHIR",
    pegawai."PENDIDIKAN",
    pegawai."TK_PENDIDIKAN",
    pegawai."TEMPAT_LAHIR_NAMA",
    pegawai."JENIS_JABATAN_NAMA",
    pegawai."JABATAN_NAMA",
    pegawai."KPKN_NAMA",
    pegawai."INSTANSI_INDUK_NAMA",
    pegawai."INSTANSI_KERJA_NAMA",
    pegawai."SATUAN_KERJA_INDUK_NAMA",
    pegawai."SATUAN_KERJA_NAMA",
    pegawai."BUP",
    golongan."NAMA" AS "GOL_TEXT",
    golongan."NAMA_PANGKAT" AS "PANGKAT_TEXT",
    agama."NAMA" AS "AGAMA_TEXT",
    jenis_kawin."NAMA" AS "KAWIN_TEXT"
   FROM (((hris.pegawai pegawai
     LEFT JOIN hris.golongan ON ((golongan."ID" = pegawai."GOL_ID")))
     LEFT JOIN hris.agama ON ((agama."ID" = pegawai."AGAMA_ID")))
     LEFT JOIN hris.jenis_kawin ON (((jenis_kawin."ID")::text = (pegawai."JENIS_KAWIN_ID")::text)));


ALTER TABLE hris.vw_drh OWNER TO postgres;

--
-- TOC entry 686 (class 1259 OID 440615)
-- Name: vw_ds_korektor; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_korektor AS
 SELECT d.id_file,
    k.id_pegawai_korektor,
    k.is_corrected,
    d.nomor_sk
   FROM (hris.tbl_file_ds d
     JOIN hris.tbl_file_ds_corrector k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND (k.is_corrected <> (1)::smallint));


ALTER TABLE hris.vw_ds_korektor OWNER TO postgres;

--
-- TOC entry 688 (class 1259 OID 440626)
-- Name: vw_ds_antrian_korektor; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_antrian_korektor AS
 SELECT d.kategori,
    k.id_pegawai_korektor,
    count(*) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.vw_ds_korektor k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text))
  GROUP BY d.kategori, k.id_pegawai_korektor;


ALTER TABLE hris.vw_ds_antrian_korektor OWNER TO postgres;

--
-- TOC entry 687 (class 1259 OID 440621)
-- Name: vw_ds_jml_korektor_new_1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_jml_korektor_new_1 AS
 SELECT d.id_file,
    count(k.id) AS jumlah_korektor
   FROM (hris.tbl_file_ds d
     JOIN hris.tbl_file_ds_corrector k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1))
  GROUP BY d.id_file;


ALTER TABLE hris.vw_ds_jml_korektor_new_1 OWNER TO postgres;

--
-- TOC entry 689 (class 1259 OID 440935)
-- Name: vw_ds_antrian_ttd; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_antrian_ttd AS
 SELECT d.kategori,
    d.id_pegawai_ttd,
    d.is_signed,
    count(*) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.vw_ds_jml_korektor_new_1 k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND ((d.kategori)::text <> '< Pilih >'::text) AND (k.jumlah_korektor > 0))
  GROUP BY d.kategori, d.is_signed, d.id_pegawai_ttd;


ALTER TABLE hris.vw_ds_antrian_ttd OWNER TO postgres;

--
-- TOC entry 854 (class 1259 OID 2122877)
-- Name: vw_ds_antrian_ttd_copy1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_antrian_ttd_copy1 AS
 SELECT d.kategori,
    d.id_pegawai_ttd,
    d.is_signed,
    count(*) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.vw_ds_jml_korektor_new_1 k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND ((d.kategori)::text <> '< Pilih >'::text) AND (k.jumlah_korektor > 0))
  GROUP BY d.kategori, d.is_signed, d.id_pegawai_ttd;


ALTER TABLE hris.vw_ds_antrian_ttd_copy1 OWNER TO postgres;

--
-- TOC entry 869 (class 1259 OID 2431241)
-- Name: vw_ds_jml_korektor; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_jml_korektor AS
 SELECT d.id_file,
    count(k.id) AS jumlah_korektor
   FROM (hris.tbl_file_ds d
     JOIN hris.tbl_file_ds_corrector k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND (d.is_signed <> (3)::smallint))
  GROUP BY d.id_file;


ALTER TABLE hris.vw_ds_jml_korektor OWNER TO postgres;

--
-- TOC entry 700 (class 1259 OID 522588)
-- Name: vw_ds_jml_korektor_new; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_jml_korektor_new AS
 SELECT d.id_file,
    count(k.id) AS jumlah_korektor
   FROM (hris.tbl_file_ds d
     JOIN hris.tbl_file_ds_corrector k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE (d.ds_ok = 1)
  GROUP BY d.id_file;


ALTER TABLE hris.vw_ds_jml_korektor_new OWNER TO postgres;

--
-- TOC entry 701 (class 1259 OID 522593)
-- Name: vw_ds_jumlah_pernip; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_jumlah_pernip AS
 SELECT d.nip_sk,
    d.is_signed,
    count(*) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.vw_ds_jml_korektor_new k ON (((d.id_file)::text = (k.id_file)::text)))
  WHERE ((d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND (k.jumlah_korektor > 0))
  GROUP BY d.is_signed, d.nip_sk;


ALTER TABLE hris.vw_ds_jumlah_pernip OWNER TO postgres;

--
-- TOC entry 871 (class 1259 OID 2431335)
-- Name: vw_ds_pejabat_ttd_dan_korektor; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_pejabat_ttd_dan_korektor AS
 SELECT d."PNS_ID",
    d."NAMA"
   FROM hris.pegawai d
  WHERE (((d."PNS_ID")::text IN ( SELECT tbl_file_ds.id_pegawai_ttd
           FROM hris.tbl_file_ds
          WHERE ((tbl_file_ds.ds_ok = '1'::smallint) AND (tbl_file_ds.is_signed <> '1'::smallint) AND (tbl_file_ds.is_signed <> '3'::smallint)))) OR ((d."PNS_ID")::text IN ( SELECT tbl_file_ds_corrector.id_pegawai_korektor
           FROM hris.tbl_file_ds_corrector
          WHERE ((tbl_file_ds_corrector.is_corrected = '2'::smallint) AND ((tbl_file_ds_corrector.id_file)::text IN ( SELECT tbl_file_ds.id_file
                   FROM hris.tbl_file_ds
                  WHERE ((tbl_file_ds.ds_ok = '1'::smallint) AND (tbl_file_ds.is_signed <> '1'::smallint) AND (tbl_file_ds.is_signed <> '3'::smallint))))))));


ALTER TABLE hris.vw_ds_pejabat_ttd_dan_korektor OWNER TO postgres;

--
-- TOC entry 737 (class 1259 OID 1581786)
-- Name: vw_ds_siap_koreksi; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_siap_koreksi AS
 SELECT k.id_pegawai_korektor,
    p."NAMA" AS nama_korektor,
    count(*) AS jumlah
   FROM ((hris.tbl_file_ds d
     JOIN hris.tbl_file_ds_corrector k ON (((d.id_file)::text = (k.id_file)::text)))
     JOIN hris.pegawai p ON (((p."PNS_ID")::text = (k.id_pegawai_korektor)::text)))
  WHERE ((d.is_signed = (0)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND (k.is_corrected = 2))
  GROUP BY k.id_pegawai_korektor, p."NAMA";


ALTER TABLE hris.vw_ds_siap_koreksi OWNER TO postgres;

--
-- TOC entry 738 (class 1259 OID 1581791)
-- Name: vw_ds_siap_ttd; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_siap_ttd AS
 SELECT d.id_pegawai_ttd,
    p."NAMA" AS nama_penandatangan,
    count(*) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.pegawai p ON (((p."PNS_ID")::text = (d.id_pegawai_ttd)::text)))
  WHERE ((d.is_signed = (0)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text) AND (d.is_corrected = 1))
  GROUP BY d.id_pegawai_ttd, p."NAMA";


ALTER TABLE hris.vw_ds_siap_ttd OWNER TO postgres;

--
-- TOC entry 872 (class 1259 OID 2431343)
-- Name: vw_ds_resume_ttd; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_resume_ttd AS
 SELECT d.id_pegawai_ttd,
    p."NAMA" AS nama_penandatangan,
    COALESCE(( SELECT (sk.jumlah)::text AS jumlah
           FROM hris.vw_ds_siap_koreksi sk
          WHERE ((sk.id_pegawai_korektor)::text = (d.id_pegawai_ttd)::text)), '-'::text) AS jml_siap_koreksi,
    COALESCE(( SELECT (s.jumlah)::text AS jumlah
           FROM hris.vw_ds_siap_ttd s
          WHERE ((s.id_pegawai_ttd)::text = (d.id_pegawai_ttd)::text)), '-'::text) AS jml_siap_ttd,
    (COALESCE(( SELECT sk.jumlah
           FROM hris.vw_ds_siap_koreksi sk
          WHERE ((sk.id_pegawai_korektor)::text = (d.id_pegawai_ttd)::text)), (0)::bigint) + COALESCE(( SELECT s.jumlah
           FROM hris.vw_ds_siap_ttd s
          WHERE ((s.id_pegawai_ttd)::text = (d.id_pegawai_ttd)::text)), (0)::bigint)) AS jumlah
   FROM (hris.tbl_file_ds d
     JOIN hris.pegawai p ON (((p."PNS_ID")::text = (d.id_pegawai_ttd)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text))
  GROUP BY d.id_pegawai_ttd, p."NAMA";


ALTER TABLE hris.vw_ds_resume_ttd OWNER TO postgres;

--
-- TOC entry 870 (class 1259 OID 2431269)
-- Name: vw_ds_resume_ttd_copy1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_resume_ttd_copy1 AS
 SELECT d.id_pegawai_ttd,
    p."NAMA" AS nama_penandatangan,
    count(*) AS jumlah,
    ( SELECT s.jumlah
           FROM hris.vw_ds_siap_ttd s
          WHERE ((s.id_pegawai_ttd)::text = (d.id_pegawai_ttd)::text)) AS jml_siap_ttd,
    ( SELECT sk.jumlah
           FROM hris.vw_ds_siap_koreksi sk
          WHERE ((sk.id_pegawai_korektor)::text = (d.id_pegawai_ttd)::text)) AS jml_siap_koreksi
   FROM ((hris.tbl_file_ds d
     JOIN hris.vw_ds_jml_korektor k ON (((d.id_file)::text = (k.id_file)::text)))
     JOIN hris.pegawai p ON (((p."PNS_ID")::text = (d.id_pegawai_ttd)::text)))
  WHERE ((d.is_signed <> (1)::smallint) AND (d.ds_ok = 1) AND ((d.kategori)::text <> '< Semua >'::text))
  GROUP BY d.id_pegawai_ttd, p."NAMA";


ALTER TABLE hris.vw_ds_resume_ttd_copy1 OWNER TO postgres;

--
-- TOC entry 873 (class 1259 OID 2431356)
-- Name: vw_ds_resume_ttd_copy3; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_ds_resume_ttd_copy3 AS
 SELECT d."PNS_ID" AS id_pegawai_ttd,
    d."NAMA" AS nama_penandatangan,
    COALESCE(( SELECT (sk.jumlah)::text AS jumlah
           FROM hris.vw_ds_siap_koreksi sk
          WHERE ((sk.id_pegawai_korektor)::text = (d."PNS_ID")::text)), '-'::text) AS jml_siap_koreksi,
    COALESCE(( SELECT (s.jumlah)::text AS jumlah
           FROM hris.vw_ds_siap_ttd s
          WHERE ((s.id_pegawai_ttd)::text = (d."PNS_ID")::text)), '-'::text) AS jml_siap_ttd,
    '-'::text AS jumlah
   FROM hris.vw_ds_pejabat_ttd_dan_korektor d;


ALTER TABLE hris.vw_ds_resume_ttd_copy3 OWNER TO postgres;

--
-- TOC entry 821 (class 1259 OID 1778145)
-- Name: vw_duk; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_duk AS
 SELECT vw."NAMA_UNOR",
    pegawai."JENIS_JABATAN_ID",
    pegawai."JABATAN_ID",
    pegawai."JABATAN_NAMA",
    pegawai."NIP_LAMA",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    vw."ESELON_ID" AS vw_eselon_id,
    pegawai."GOL_ID",
    (((golongan."NAMA_PANGKAT")::text || ' '::text) || (golongan."NAMA")::text) AS golongan_text,
    'jabatanku'::text AS jabatan_text,
    pegawai."PNS_ID",
    (((date_part('year'::text, (now())::date) - date_part('year'::text, pegawai."TGL_LAHIR")) * (12)::double precision) + (date_part('month'::text, (now())::date) - date_part('month'::text, pegawai."TGL_LAHIR"))) AS bulan_usia,
    '#'::text AS separator,
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."KEDUDUKAN_HUKUM_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."KARTU_PEGAWAI",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."PENDIDIKAN",
    pegawai."TAHUN_LULUS",
    pegawai."KPKN_ID",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."ID",
    pegawai."PHOTO",
    pegawai."TMT_PENSIUN",
    pegawai."BUP",
    pegawai."EMAIL_DIKBUD",
    vw."ESELON_1",
    vw."ESELON_2",
    vw."ESELON_3",
    vw."ESELON_4",
    tkpendidikan."NAMA" AS "TINGKAT_PENDIDIKAN_NAMA",
    jabatan."NAMA_JABATAN",
    jabatan."KELAS",
    jabatan."PENSIUN",
    kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA"
   FROM (((((((hris.pns_aktif pa
     LEFT JOIN hris.pegawai pegawai ON ((pa."ID" = pegawai."ID")))
     LEFT JOIN hris.golongan ON (((pegawai."GOL_ID")::text = (golongan."ID")::text)))
     LEFT JOIN hris.vw_unit_list vw ON (((vw."ID")::text = (pegawai."UNOR_ID")::text)))
     LEFT JOIN hris.pendidikan ON (((pendidikan."ID")::text = (pegawai."PENDIDIKAN_ID")::text)))
     LEFT JOIN hris.tkpendidikan ON (((tkpendidikan."ID")::text = (pendidikan."TINGKAT_PENDIDIKAN_ID")::text)))
     LEFT JOIN hris.jabatan ON (((jabatan."KODE_JABATAN")::text = (pegawai."JABATAN_INSTANSI_ID")::text)))
     LEFT JOIN hris.kedudukan_hukum ON (((kedudukan_hukum."ID")::text = (pegawai."KEDUDUKAN_HUKUM_ID")::text)))
  ORDER BY pegawai."JENIS_JABATAN_ID", vw."ESELON_ID", vw."ESELON_1", vw."ESELON_2", vw."ESELON_3", vw."ESELON_4", pegawai."JABATAN_ID", vw."NAMA_UNOR_FULL", pegawai."GOL_ID" DESC, pegawai."TMT_GOLONGAN", pegawai."TMT_JABATAN", pegawai."TMT_CPNS", pegawai."TGL_LAHIR";


ALTER TABLE hris.vw_duk OWNER TO postgres;

--
-- TOC entry 820 (class 1259 OID 1778140)
-- Name: vw_duk_list; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_duk_list AS
 SELECT vw."NAMA_UNOR_FULL",
    pejabat."ESELON_ID",
    vw."ESELON_ID" AS vw_eselon_id,
    pegawai."GOL_ID",
    (((golongan."NAMA_PANGKAT")::text || ' '::text) || (golongan."NAMA")::text) AS golongan_text,
    'jabatanku'::text AS jabatan_text,
    pegawai."PNS_ID",
    pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."GELAR_DEPAN",
    pegawai."GELAR_BELAKANG",
    (((date_part('year'::text, (now())::date) - date_part('year'::text, pegawai."TGL_LAHIR")) * (12)::double precision) + (date_part('month'::text, (now())::date) - date_part('month'::text, pegawai."TGL_LAHIR"))) AS bulan_usia,
    '#'::text AS separator,
    pegawai."NIP_LAMA",
    pegawai."TEMPAT_LAHIR_ID",
    pegawai."TGL_LAHIR",
    pegawai."JENIS_KELAMIN",
    pegawai."AGAMA_ID",
    pegawai."JENIS_KAWIN_ID",
    pegawai."NIK",
    pegawai."NOMOR_DARURAT",
    pegawai."NOMOR_HP",
    pegawai."EMAIL",
    pegawai."ALAMAT",
    pegawai."NPWP",
    pegawai."BPJS",
    pegawai."JENIS_PEGAWAI_ID",
    pegawai."KEDUDUKAN_HUKUM_ID",
    pegawai."STATUS_CPNS_PNS",
    pegawai."KARTU_PEGAWAI",
    pegawai."NOMOR_SK_CPNS",
    pegawai."TGL_SK_CPNS",
    pegawai."TMT_CPNS",
    pegawai."TMT_PNS",
    pegawai."GOL_AWAL_ID",
    pegawai."TMT_GOLONGAN",
    pegawai."MK_TAHUN",
    pegawai."MK_BULAN",
    pegawai."JENIS_JABATAN_IDx" AS "JENIS_JABATAN_ID",
    pegawai."JABATAN_ID",
    pegawai."JABATAN_NAMA",
    pegawai."TMT_JABATAN",
    pegawai."PENDIDIKAN_ID",
    pegawai."PENDIDIKAN",
    pegawai."TAHUN_LULUS",
    pegawai."KPKN_ID",
    pegawai."LOKASI_KERJA_ID",
    pegawai."UNOR_ID",
    pegawai."UNOR_INDUK_ID",
    pegawai."INSTANSI_INDUK_ID",
    pegawai."INSTANSI_KERJA_ID",
    pegawai."SATUAN_KERJA_INDUK_ID",
    pegawai."SATUAN_KERJA_KERJA_ID",
    pegawai."GOLONGAN_DARAH",
    pegawai."ID",
    pegawai."PHOTO",
    pegawai."TMT_PENSIUN",
    pegawai."BUP",
    vw."NAMA_UNOR",
    vw."ESELON_1",
    vw."ESELON_2",
    vw."ESELON_3",
    vw."ESELON_4"
   FROM ((((hris.pns_aktif_old pa
     LEFT JOIN hris.pegawai pegawai ON ((pa."ID" = pegawai."ID")))
     LEFT JOIN hris.golongan ON (((pegawai."GOL_ID")::text = (golongan."ID")::text)))
     LEFT JOIN hris.vw_unit_list vw ON (((vw."ID")::text = (pegawai."UNOR_ID")::text)))
     LEFT JOIN hris.unitkerja pejabat ON (((pejabat."PEMIMPIN_PNS_ID")::text = (pegawai."PNS_ID")::text)))
  ORDER BY vw."NAMA_UNOR_FULL", pejabat."ESELON_ID", pegawai."GOL_ID" DESC, pegawai."TMT_GOLONGAN", pegawai."TMT_JABATAN", pegawai."TMT_CPNS", pegawai."TGL_LAHIR";


ALTER TABLE hris.vw_duk_list OWNER TO postgres;

--
-- TOC entry 819 (class 1259 OID 1778135)
-- Name: vw_kgb; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_kgb AS
 SELECT hris.get_masa_kerja_arr(temp."TMT_CPNS", temp.get_kgb_yad) AS get_masa_kerja_arr,
    temp.get_kgb_yad,
    temp."ID",
    temp."PNS_ID",
    temp."NIP_LAMA",
    temp."NIP_BARU",
    temp."NAMA",
    temp."GELAR_DEPAN",
    temp."GELAR_BELAKANG",
    temp."TEMPAT_LAHIR_ID",
    temp."TGL_LAHIR",
    temp."JENIS_KELAMIN",
    temp."AGAMA_ID",
    temp."JENIS_KAWIN_ID",
    temp."NIK",
    temp."NOMOR_DARURAT",
    temp."NOMOR_HP",
    temp."EMAIL",
    temp."ALAMAT",
    temp."NPWP",
    temp."BPJS",
    temp."JENIS_PEGAWAI_ID",
    temp."KEDUDUKAN_HUKUM_ID",
    temp."STATUS_CPNS_PNS",
    temp."KARTU_PEGAWAI",
    temp."NOMOR_SK_CPNS",
    temp."TGL_SK_CPNS",
    temp."TMT_CPNS",
    temp."TMT_PNS",
    temp."GOL_AWAL_ID",
    temp."GOL_ID",
    temp."TMT_GOLONGAN",
    temp."MK_TAHUN",
    temp."MK_BULAN",
    temp."JENIS_JABATAN_IDx",
    temp."JABATAN_ID",
    temp."TMT_JABATAN",
    temp."PENDIDIKAN_ID",
    temp."TAHUN_LULUS",
    temp."KPKN_ID",
    temp."LOKASI_KERJA_ID",
    temp."UNOR_ID",
    temp."UNOR_INDUK_ID",
    temp."INSTANSI_INDUK_ID",
    temp."INSTANSI_KERJA_ID",
    temp."SATUAN_KERJA_INDUK_ID",
    temp."SATUAN_KERJA_KERJA_ID",
    temp."GOLONGAN_DARAH",
    temp."PHOTO",
    temp."TMT_PENSIUN",
    temp."LOKASI_KERJA",
    temp."JML_ISTRI",
    temp."JML_ANAK",
    temp."NO_SURAT_DOKTER",
    temp."TGL_SURAT_DOKTER",
    temp."NO_BEBAS_NARKOBA",
    temp."TGL_BEBAS_NARKOBA",
    temp."NO_CATATAN_POLISI",
    temp."TGL_CATATAN_POLISI",
    temp."AKTE_KELAHIRAN",
    temp."STATUS_HIDUP",
    temp."AKTE_MENINGGAL",
    temp."TGL_MENINGGAL",
    temp."NO_ASKES",
    temp."NO_TASPEN",
    temp."TGL_NPWP",
    temp."TEMPAT_LAHIR",
    temp."PENDIDIKAN",
    temp."TK_PENDIDIKAN",
    temp."TEMPAT_LAHIR_NAMA",
    temp."JENIS_JABATAN_NAMA",
    temp."JABATAN_NAMA",
    temp."KPKN_NAMA",
    temp."INSTANSI_INDUK_NAMA",
    temp."INSTANSI_KERJA_NAMA",
    temp."SATUAN_KERJA_INDUK_NAMA",
    temp."SATUAN_KERJA_NAMA",
    temp."JABATAN_INSTANSI_ID",
    temp."BUP",
    temp."JABATAN_INSTANSI_NAMA",
    temp."JENIS_JABATAN_ID",
    temp.terminated_date,
    temp.status_pegawai
   FROM ( SELECT hris.get_kgb_yad(p."TMT_CPNS") AS get_kgb_yad,
            p."ID",
            p."ID" AS pegawai_id,
            p."PNS_ID",
            p."NIP_LAMA",
            p."NIP_BARU",
            p."NAMA",
            p."GELAR_DEPAN",
            p."GELAR_BELAKANG",
            p."TEMPAT_LAHIR_ID",
            p."TGL_LAHIR",
            p."JENIS_KELAMIN",
            p."AGAMA_ID",
            p."JENIS_KAWIN_ID",
            p."NIK",
            p."NOMOR_DARURAT",
            p."NOMOR_HP",
            p."EMAIL",
            p."ALAMAT",
            p."NPWP",
            p."BPJS",
            p."JENIS_PEGAWAI_ID",
            p."KEDUDUKAN_HUKUM_ID",
            p."STATUS_CPNS_PNS",
            p."KARTU_PEGAWAI",
            p."NOMOR_SK_CPNS",
            p."TGL_SK_CPNS",
            p."TMT_CPNS",
            p."TMT_PNS",
            p."GOL_AWAL_ID",
            p."GOL_ID",
            p."TMT_GOLONGAN",
            p."MK_TAHUN",
            p."MK_BULAN",
            p."JENIS_JABATAN_IDx",
            p."JABATAN_ID",
            p."TMT_JABATAN",
            p."PENDIDIKAN_ID",
            p."TAHUN_LULUS",
            p."KPKN_ID",
            p."LOKASI_KERJA_ID",
            p."UNOR_ID",
            p."UNOR_INDUK_ID",
            p."INSTANSI_INDUK_ID",
            p."INSTANSI_KERJA_ID",
            p."SATUAN_KERJA_INDUK_ID",
            p."SATUAN_KERJA_KERJA_ID",
            p."GOLONGAN_DARAH",
            p."PHOTO",
            p."TMT_PENSIUN",
            p."LOKASI_KERJA",
            p."JML_ISTRI",
            p."JML_ANAK",
            p."NO_SURAT_DOKTER",
            p."TGL_SURAT_DOKTER",
            p."NO_BEBAS_NARKOBA",
            p."TGL_BEBAS_NARKOBA",
            p."NO_CATATAN_POLISI",
            p."TGL_CATATAN_POLISI",
            p."AKTE_KELAHIRAN",
            p."STATUS_HIDUP",
            p."AKTE_MENINGGAL",
            p."TGL_MENINGGAL",
            p."NO_ASKES",
            p."NO_TASPEN",
            p."TGL_NPWP",
            p."TEMPAT_LAHIR",
            p."PENDIDIKAN",
            p."TK_PENDIDIKAN",
            p."TEMPAT_LAHIR_NAMA",
            p."JENIS_JABATAN_NAMA",
            p."JABATAN_NAMA",
            p."KPKN_NAMA",
            p."INSTANSI_INDUK_NAMA",
            p."INSTANSI_KERJA_NAMA",
            p."SATUAN_KERJA_INDUK_NAMA",
            p."SATUAN_KERJA_NAMA",
            p."JABATAN_INSTANSI_ID",
            p."BUP",
            p."JABATAN_INSTANSI_NAMA",
            p."JENIS_JABATAN_ID",
            p.terminated_date,
            p.status_pegawai
           FROM (hris.pns_aktif_old pa
             JOIN hris.pegawai p ON ((pa."ID" = p."ID")))) temp;


ALTER TABLE hris.vw_kgb OWNER TO postgres;

--
-- TOC entry 729 (class 1259 OID 1275750)
-- Name: vw_list_eselon1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_list_eselon1 AS
 SELECT DISTINCT es1."NO",
    es1."KODE_INTERNAL",
    es1."ID",
    es1."NAMA_UNOR",
    es1."ESELON_ID",
    es1."CEPAT_KODE",
    es1."NAMA_JABATAN",
    es1."NAMA_PEJABAT",
    es1."DIATASAN_ID",
    es1."INSTANSI_ID",
    es1."PEMIMPIN_NON_PNS_ID",
    es1."PEMIMPIN_PNS_ID",
    es1."JENIS_UNOR_ID",
    es1."UNOR_INDUK",
    es1."JUMLAH_IDEAL_STAFF",
    es1."ORDER",
    es1.deleted,
    es1."IS_SATKER",
    es1."ESELON_1",
    es1."ESELON_2",
    es1."ESELON_3",
    es1."ESELON_4",
    es1."EXPIRED_DATE",
    es1."KETERANGAN",
    es1."ABBREVIATION"
   FROM hris.unitkerja es1
  WHERE ((es1."ID" IS NOT NULL) AND ((es1."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text) AND (es1."EXPIRED_DATE" IS NULL))
  ORDER BY es1."NAMA_UNOR";


ALTER TABLE hris.vw_list_eselon1 OWNER TO postgres;

--
-- TOC entry 507 (class 1259 OID 36425)
-- Name: vw_list_eselon2; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_list_eselon2 AS
 SELECT DISTINCT es2."NO",
    es2."KODE_INTERNAL",
    es2."ID",
    es2."NAMA_UNOR",
    es2."ESELON_ID",
    es2."CEPAT_KODE",
    es2."NAMA_JABATAN",
    es2."NAMA_PEJABAT",
    es2."DIATASAN_ID",
    es2."INSTANSI_ID",
    es2."PEMIMPIN_NON_PNS_ID",
    es2."PEMIMPIN_PNS_ID",
    es2."JENIS_UNOR_ID",
    es2."UNOR_INDUK",
    es2."JUMLAH_IDEAL_STAFF",
    es2."ORDER",
    es2.deleted,
    es2."IS_SATKER",
    es2."ESELON_1",
    es2."ESELON_2",
    es2."ESELON_3",
    es2."ESELON_4",
    es2."EXPIRED_DATE",
    es2."KETERANGAN",
    es2."ABBREVIATION"
   FROM (hris.unitkerja uk
     LEFT JOIN hris.unitkerja es2 ON (((uk."ESELON_2")::text = (es2."ID")::text)))
  WHERE (es2."ID" IS NOT NULL)
  ORDER BY es2."NAMA_UNOR";


ALTER TABLE hris.vw_list_eselon2 OWNER TO postgres;

--
-- TOC entry 868 (class 1259 OID 2430168)
-- Name: vw_pegawai_berpotensi_jpt; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pegawai_berpotensi_jpt AS
 SELECT apbt.id,
    apbt.nip,
    apbt.usia,
    apbt.status_kepegawaian,
    apbt.golongan,
    apbt.jenis_jabatan,
    apbt.jabatan,
    apbt.tmt,
    apbt.lama_jabatan_terakhir,
    apbt.eselon,
    apbt.satker,
    apbt.unit_organisasi_induk,
    apbt.kedudukan,
    apbt.tipe,
    apbt.pendidikan,
    apbt.jabatan_madya_lain,
    apbt.tmt_jabatan_madya_lain,
    apbt.jabatan_struktural_lain,
    apbt.tmt_jabatan_struktural_lain,
    apbt.lama_menjabat_akumulasi,
    apbt.rekam_jejak,
    apbt.skp,
    tp."NAMA" AS tingkat_pendidikan,
    pd."NAMA" AS nama_pendidikan,
    aha.tanggal_asesmen,
    aha.jpm
   FROM ((((hris.asesmen_pegawai_berpotensi_jpt apbt
     LEFT JOIN hris.asesmen_hasil_asesmen aha ON ((btrim((apbt.nip)::text) = btrim((aha.nip)::text))))
     LEFT JOIN hris.pegawai p ON (((p."NIP_BARU")::text = (apbt.nip)::text)))
     LEFT JOIN hris.pendidikan pd ON (((p."PENDIDIKAN_ID")::text = (pd."ID")::text)))
     LEFT JOIN hris.tkpendidikan tp ON (((tp."ID")::text = (pd."TINGKAT_PENDIDIKAN_ID")::text)));


ALTER TABLE hris.vw_pegawai_berpotensi_jpt OWNER TO postgres;

--
-- TOC entry 818 (class 1259 OID 1778117)
-- Name: vw_pegawai_bpk; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pegawai_bpk AS
 SELECT pegawai."NAMA" AS "NAMA_PEGAWAI",
    (''''::text || (pegawai."NIP_BARU")::text) AS "NIP_BARU",
    (''''::text || (pegawai."NIK")::text) AS "NIK",
    lokasi."NAMA" AS "TEMPAT_LAHIR",
    pegawai."TGL_LAHIR" AS "TANGGAL_LAHIR",
    agama."NAMA" AS "AGAMA",
    pegawai."JENIS_KELAMIN",
    tkpendidikan."NAMA" AS "TINGKAT_PENDIDIKAN",
    pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
    (((pegawai."MK_TAHUN")::text || '/'::text) || (pegawai."MK_BULAN")::text) AS "MASA_KERJA",
    golongan."NAMA" AS "PANGKAT_GOLONGAN_AKTIF",
    golongan_awal."NAMA" AS "GOLONGAN_AWAL",
    'PNS PUSAT'::text AS "JENIS_PEGAWAI",
    pegawai."TMT_GOLONGAN",
    pegawai."TMT_CPNS",
    pegawai."STATUS_CPNS_PNS",
    jenis_kawin."NAMA" AS "JENIS_KAWIN",
    pegawai."NPWP",
    pegawai."EMAIL",
    pegawai."EMAIL_DIKBUD",
    pegawai."NOMOR_HP",
    jabatan."NAMA_JABATAN",
    jabatan."KATEGORI_JABATAN",
    vw."NAMA_UNOR",
    vw."NAMA_UNOR_FULL"
   FROM ((((((((((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
     LEFT JOIN hris.jabatan ON ((pegawai."JABATAN_INSTANSI_ID" = (jabatan."KODE_JABATAN")::bpchar)))
     LEFT JOIN hris.lokasi ON (((pegawai."TEMPAT_LAHIR_ID")::text = (lokasi."ID")::text)))
     LEFT JOIN hris.agama ON ((pegawai."AGAMA_ID" = agama."ID")))
     LEFT JOIN hris.tkpendidikan ON ((pegawai."TK_PENDIDIKAN" = (tkpendidikan."ID")::bpchar)))
     LEFT JOIN hris.pendidikan ON (((pegawai."PENDIDIKAN_ID")::text = (pendidikan."ID")::text)))
     LEFT JOIN hris.golongan ON ((pegawai."GOL_ID" = golongan."ID")))
     LEFT JOIN hris.golongan golongan_awal ON (((pegawai."GOL_AWAL_ID")::text = (golongan_awal."ID")::text)))
     LEFT JOIN hris.jenis_kawin ON (((jenis_kawin."ID")::text = (pegawai."JENIS_KAWIN_ID")::text)))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> ALL (ARRAY[('14'::character varying)::text, ('52'::character varying)::text, ('66'::character varying)::text, ('67'::character varying)::text, ('77'::character varying)::text, ('88'::character varying)::text, ('98'::character varying)::text, ('99'::character varying)::text, ('100'::character varying)::text])) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)));


ALTER TABLE hris.vw_pegawai_bpk OWNER TO postgres;

--
-- TOC entry 851 (class 1259 OID 2106962)
-- Name: vw_pegawai_for_bpjstk; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pegawai_for_bpjstk AS
 SELECT mvp."NIK",
    mvp."NAMA" AS nama_pegawai,
    (((l."NAMA")::text || ' / '::text) || mvp."TGL_LAHIR") AS tempat_tanggal_lahir,
    mvp."NOMOR_HP" AS nomor_telepon,
    mvp."EMAIL" AS email,
    mvp."NAMA_UNOR_FULL" AS unit_kerja,
    mvp."NAMA_JABATAN_REAL" AS jabatan,
    mvp."KATEGORI_JABATAN_REAL" AS jenis_jabatan,
    mvp."NAMA_GOLONGAN" AS golongan,
        CASE
            WHEN ((mvp."JENIS_PEGAWAI_ID" = '71'::text) OR (mvp."JENIS_PEGAWAI_ID" = '72'::text) OR (mvp."JENIS_PEGAWAI_ID" = '73'::text)) THEN 'PPPK'::text
            ELSE 'PNS'::text
        END AS status_kepegawaian,
    mvp."UNOR_ID",
    mvp."UNOR_INDUK_ID"
   FROM (hris.mv_pegawai mvp
     LEFT JOIN hris.lokasi l ON ((mvp."TEMPAT_LAHIR_ID" = (l."ID")::text)));


ALTER TABLE hris.vw_pegawai_for_bpjstk OWNER TO postgres;

--
-- TOC entry 816 (class 1259 OID 1778095)
-- Name: vw_pegawai_simple; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pegawai_simple AS
 SELECT pegawai."ID",
    pegawai."NIP_BARU",
    btrim((pegawai."NAMA")::text) AS "NAMA",
    btrim((pegawai."UNOR_INDUK_ID")::text) AS "UNOR_INDUK_ID",
    btrim((pegawai."UNOR_ID")::text) AS "UNOR_ID",
    vw."ESELON_1" AS "VW_ESELON_1",
    vw."ESELON_2" AS "VW_ESELON_2",
    vw."ESELON_3" AS "VW_ESELON_3",
    vw."UNOR_INDUK" AS "VW_UNOR_INDUK"
   FROM ((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '99'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '66'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '52'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '20'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '04'::text) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)));


ALTER TABLE hris.vw_pegawai_simple OWNER TO postgres;

--
-- TOC entry 817 (class 1259 OID 1778100)
-- Name: vw_pegawai_tanpa_akun; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pegawai_tanpa_akun AS
 SELECT pegawai."NIP_BARU"
   FROM (((hris.pegawai pegawai
     LEFT JOIN hris.vw_unit_list vw ON (((pegawai."UNOR_ID")::text = (vw."ID")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
     LEFT JOIN hris.jabatan ON ((pegawai."JABATAN_INSTANSI_ID" = (jabatan."KODE_JABATAN")::bpchar)))
  WHERE ((pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> ALL (ARRAY[('14'::character varying)::text, ('52'::character varying)::text, ('66'::character varying)::text, ('67'::character varying)::text, ('77'::character varying)::text, ('78'::character varying)::text, ('98'::character varying)::text, ('99'::character varying)::text])) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)) AND (NOT ((pegawai."NIP_BARU")::text IN ( SELECT users.username
           FROM hris.users))));


ALTER TABLE hris.vw_pegawai_tanpa_akun OWNER TO postgres;

--
-- TOC entry 704 (class 1259 OID 857479)
-- Name: vw_pejabat_cuti; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_pejabat_cuti AS
 SELECT line_approval_izin."NIP_ATASAN",
    count(*) AS jumlah
   FROM hris.line_approval_izin
  GROUP BY line_approval_izin."NIP_ATASAN";


ALTER TABLE hris.vw_pejabat_cuti OWNER TO postgres;

--
-- TOC entry 850 (class 1259 OID 1861374)
-- Name: vw_rwt_asesmen_terakhir; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_rwt_asesmen_terakhir AS
 SELECT concat('|', pegawai."NIP_BARU") AS "NIP_BARU",
    pegawai."NAMA",
    pegawai."JENIS_KELAMIN",
    golongan."NAMA" AS golongan,
    pegawai."UNOR_ID" AS "UNOR_PEGAWAI",
    pegawai."SATUAN_KERJA_INDUK_ID" AS "SATKER_PEGAWAI",
    pegawai."JABATAN_NAMA",
    pegawai."JENIS_JABATAN_NAMA",
    vw_unor_satker."NAMA_UNOR",
    vw_unor_satker."NAMA_SATKER",
    vw_unor_satker."NAMA_UNOR_ESELON_1",
    rwt_assesmen."ID",
    rwt_assesmen."PNS_NIP",
    max((rwt_assesmen."TAHUN")::integer) AS last_asesmen
   FROM ((((hris.pegawai pegawai
     LEFT JOIN hris.golongan ON ((pegawai."GOL_ID" = golongan."ID")))
     LEFT JOIN hris.rwt_assesmen ON (((pegawai."NIP_BARU")::bpchar = rwt_assesmen."PNS_NIP")))
     LEFT JOIN hris.vw_unor_satker ON (((pegawai."UNOR_ID")::text = (vw_unor_satker."ID_UNOR")::text)))
     LEFT JOIN hris.pns_aktif pa ON ((pegawai."ID" = pa."ID")))
  WHERE ((pegawai.status_pegawai = 1) AND ((pegawai.terminated_date IS NULL) OR ((pegawai.terminated_date IS NOT NULL) AND (pegawai.terminated_date > ('now'::text)::date))) AND (pa."ID" IS NOT NULL) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '99'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '66'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '52'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '20'::text) AND ((pegawai."KEDUDUKAN_HUKUM_ID")::text <> '04'::text) AND ((pegawai.status_pegawai <> 3) OR (pegawai.status_pegawai IS NULL)))
  GROUP BY pegawai."NIP_BARU", pegawai."NAMA", pegawai."JENIS_KELAMIN", golongan."NAMA", pegawai."UNOR_ID", pegawai."SATUAN_KERJA_INDUK_ID", pegawai."JABATAN_NAMA", pegawai."JENIS_JABATAN_NAMA", vw_unor_satker."NAMA_UNOR", vw_unor_satker."NAMA_SATKER", vw_unor_satker."NAMA_UNOR_ESELON_1", rwt_assesmen."ID", rwt_assesmen."PNS_ID", rwt_assesmen."PNS_NIP";


ALTER TABLE hris.vw_rwt_asesmen_terakhir OWNER TO postgres;

--
-- TOC entry 849 (class 1259 OID 1861367)
-- Name: vw_rwt_assesmen_pegawai; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_rwt_assesmen_pegawai AS
 SELECT pegawai."NIP_BARU",
    pegawai."NAMA",
    pegawai."UNOR_ID" AS "UNOR_PEGAWAI",
    pegawai."UNOR_INDUK_ID" AS "UNOR_INDUK_PEGAWAI",
    pegawai."SATUAN_KERJA_INDUK_ID" AS "SATKER_PEGAWAI",
    rwt_assesmen."ID",
    rwt_assesmen."PNS_ID",
    rwt_assesmen."PNS_NIP",
    rwt_assesmen."TAHUN",
    rwt_assesmen."FILE_UPLOAD",
    rwt_assesmen."NILAI",
    rwt_assesmen."NILAI_KINERJA",
    rwt_assesmen."TAHUN_PENILAIAN_ID",
    rwt_assesmen."TAHUN_PENILAIAN_TITLE",
    rwt_assesmen."FULLNAME",
    rwt_assesmen."POSISI_ID",
    rwt_assesmen."UNIT_ORG_ID",
    rwt_assesmen."NAMA_UNOR",
    rwt_assesmen."SARANPENGEMBANGAN",
    rwt_assesmen."FILE_UPLOAD_FB_POTENSI",
    rwt_assesmen."FILE_UPLOAD_LENGKAP_PT",
    rwt_assesmen."FILE_UPLOAD_FB_PT",
    rwt_assesmen."FILE_UPLOAD_EXISTS",
    rwt_assesmen."SATKER_ID",
    vw_unor_satker."NAMA_UNOR" AS "NAMA_UNOR_PEGAWAI"
   FROM ((hris.pegawai pegawai
     LEFT JOIN hris.rwt_assesmen ON (((pegawai."NIP_BARU")::bpchar = rwt_assesmen."PNS_NIP")))
     LEFT JOIN hris.vw_unor_satker ON (((pegawai."UNOR_ID")::text = (vw_unor_satker."ID_UNOR")::text)))
  WHERE ((rwt_assesmen."ID" IS NOT NULL) AND (pegawai.status_pegawai = 1) AND ((pegawai.terminated_date IS NULL) OR ((pegawai.terminated_date IS NOT NULL) AND (pegawai.terminated_date > ('now'::text)::date))));


ALTER TABLE hris.vw_rwt_assesmen_pegawai OWNER TO postgres;

--
-- TOC entry 855 (class 1259 OID 2125101)
-- Name: vw_rwt_diklat; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_rwt_diklat AS
 SELECT rwt_diklat.id,
    jenis_diklat_siasn.jenis_diklat,
    jenis_diklat_siasn.id AS jenis_diklat_id,
    rwt_diklat.institusi_penyelenggara,
    rwt_diklat.nomor_sertifikat,
    rwt_diklat.tanggal_mulai,
    rwt_diklat.tanggal_selesai,
    rwt_diklat.tahun_diklat,
    rwt_diklat.durasi_jam,
    rwt_diklat.nip_baru,
    rwt_diklat.createddate,
    rwt_diklat.nama_diklat,
    jenis_rumpun_diklat_siasn.nama AS rumpun_diklat,
    jenis_rumpun_diklat_siasn.id AS rumpun_diklat_id,
    rwt_diklat.sudah_kirim_siasn,
    pegawai."PNS_ID" AS pns_orang_id,
    rwt_diklat.siasn_id,
    rwt_diklat.diklat_struktural_id
   FROM (((hris.rwt_diklat
     LEFT JOIN hris.jenis_diklat_siasn ON (((rwt_diklat.jenis_diklat_id)::text = (jenis_diklat_siasn.id)::text)))
     LEFT JOIN hris.jenis_rumpun_diklat_siasn ON (((jenis_rumpun_diklat_siasn.id)::text = (rwt_diklat.rumpun_diklat_id)::text)))
     LEFT JOIN hris.pegawai ON (((pegawai."NIP_BARU")::text = (rwt_diklat.nip_baru)::text)));


ALTER TABLE hris.vw_rwt_diklat OWNER TO postgres;

--
-- TOC entry 825 (class 1259 OID 1778178)
-- Name: vw_skp; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_skp AS
 SELECT s."ID",
    s."PNS_NIP",
    s."PNS_NAMA",
    s."ATASAN_LANGSUNG_PNS_NAMA",
    s."ATASAN_LANGSUNG_PNS_NIP",
    s."NILAI_SKP",
    s."NILAI_PROSENTASE_SKP",
    s."NILAI_SKP_AKHIR",
    s."PERILAKU_KOMITMEN",
    s."PERILAKU_INTEGRITAS",
    s."PERILAKU_DISIPLIN",
    s."PERILAKU_KERJASAMA",
    s."PERILAKU_ORIENTASI_PELAYANAN",
    s."PERILAKU_KEPEMIMPINAN",
    s."NILAI_PERILAKU",
    s."NILAI_PROSENTASE_PERILAKU",
    s."NILAI_PERILAKU_AKHIR",
    s."NILAI_PPK",
    s."TAHUN",
    s."JABATAN_TIPE",
    s."PNS_ID",
    s."ATASAN_LANGSUNG_PNS_ID",
    s."ATASAN_ATASAN_LANGSUNG_PNS_ID",
    s."ATASAN_ATASAN_LANGSUNG_PNS_NAMA",
    s."ATASAN_ATASAN_LANGSUNG_PNS_NIP",
    s."JABATAN_TIPE_TEXT",
    s."ATASAN_LANGSUNG_PNS_JABATAN",
    s."ATASAN_ATASAN_LANGSUNG_PNS_JABATAN",
    s."JABATAN_NAMA",
    s."BKN_ID",
    s."UNOR_PENILAI",
    s."UNOR_ATASAN_PENILAI",
    s."ATASAN_PENILAI_PNS",
    s."PENILAI_PNS",
    s."GOL_PENILAI",
    s."GOL_ATASAN_PENILAI",
    s."TMT_GOL_PENILAI",
    s."TMT_GOL_ATASAN_PENILAI",
    s."PERATURAN",
    s.created_date,
    s.updated_date,
    s."PERILAKU_INISIATIF_KERJA",
    a."NIP_BARU" AS nip_atasan,
    a."NAMA" AS nama_atasan,
    a."PNS_ID" AS pns_id_atasan,
    aa."NIP_BARU" AS a_nip_atasan,
    aa."NAMA" AS a_nama_atasan,
    aa."PNS_ID" AS a_pns_id_atasan,
    ag."ID" AS golongan_atasan,
    a."TMT_GOLONGAN" AS tmt_golongan_atasan,
    aag."ID" AS golongan_atasan_atasan,
    aa."TMT_GOLONGAN" AS tmt_golongan_atasan_atasan,
    a.status_pegawai AS status_pns_atasan,
    aa.status_pegawai AS status_pns_atasan_atasan,
    p."JENIS_JABATAN_ID",
    au."NAMA_UNOR" AS nama_unor_atasan,
    aau."NAMA_UNOR" AS nama_unor_atasan_atasan
   FROM (((((((hris.rwt_prestasi_kerja s
     LEFT JOIN hris.pegawai p ON (((s."PNS_NIP")::text = (p."NIP_BARU")::text)))
     LEFT JOIN hris.pegawai a ON (((s."ATASAN_LANGSUNG_PNS_NIP")::text = (a."NIP_BARU")::text)))
     LEFT JOIN hris.pegawai aa ON (((s."ATASAN_ATASAN_LANGSUNG_PNS_NIP")::text = (aa."NIP_BARU")::text)))
     LEFT JOIN hris.golongan ag ON (((a."GOL_ID")::text = (ag."ID")::text)))
     LEFT JOIN hris.golongan aag ON (((aa."GOL_ID")::text = (aag."ID")::text)))
     LEFT JOIN hris.vw_unit_list au ON (((a."UNOR_ID")::text = (au."ID")::text)))
     LEFT JOIN hris.vw_unit_list aau ON (((aa."UNOR_ID")::text = (aau."ID")::text)));


ALTER TABLE hris.vw_skp OWNER TO postgres;

--
-- TOC entry 702 (class 1259 OID 522599)
-- Name: vw_sync_ds; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_sync_ds AS
 SELECT ld."ID",
    ld."NIK",
    ld."ID_FILE",
    ld."CREATED_DATE",
    ld."STATUS",
    ld."PROSES_CRON",
    btrim((ds.kategori)::text) AS kategori,
    btrim((ds.nip_sk)::text) AS nip_sk,
    ds.nama_pemilik_sk
   FROM (hris.log_ds ld
     LEFT JOIN hris.tbl_file_ds ds ON (((ld."ID_FILE")::text = (ds.id_file)::text)))
  WHERE ((ld."STATUS" = 2) AND (ds.nip_sk IS NOT NULL) AND (ds.telah_kirim = 1) AND (ds.ds_ok = 1) AND (ld."PROSES_CRON" = 0) AND (ds.is_signed = 1));


ALTER TABLE hris.vw_sync_ds OWNER TO postgres;

--
-- TOC entry 735 (class 1259 OID 1530318)
-- Name: vw_tte_trx_draft_sk_to_sk; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_tte_trx_draft_sk_to_sk AS
 SELECT ttdsk.id,
    ttdsk.id_master_proses,
    ttdsk.nip_sk,
    ttdsk.penandatangan_sk,
    ttdsk.tgl_sk,
    ttdsk.nomor_sk,
    ttdsk.file_template,
    ttdsk.base64pdf_hasil,
    ttdsk.created_date,
    ttdsk.created_by,
    ttdsk.updated_date,
    ttdsk.updated_by,
    ttdsk.id_file,
    ttdsk.tmt_sk,
    ttdsk.nama_pemilik_sk,
    ttdsk.halaman_ttd,
    ttdsk.show_qrcode,
    ttdsk.letak_ttd,
    tfd.is_signed
   FROM (hris.tte_trx_draft_sk ttdsk
     JOIN hris.tbl_file_ds tfd ON (((ttdsk.id_file)::text = (tfd.id_file)::text)));


ALTER TABLE hris.vw_tte_trx_draft_sk_to_sk OWNER TO postgres;

--
-- TOC entry 508 (class 1259 OID 36435)
-- Name: vw_unit_list_asli; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unit_list_asli AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."ESELON_1",
    uk."ESELON_2",
    uk."ESELON_3",
    uk."ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    btrim(concat(es1."NAMA_UNOR", '-', es2."NAMA_UNOR", '-', es3."NAMA_UNOR", '-', es4."NAMA_UNOR"), '-'::text) AS "NAMA_UNOR_FULL"
   FROM ((((hris.unitkerja_1234 uk
     LEFT JOIN hris.unitkerja_1234 es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja_1234 es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja_1234 es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja_1234 es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)));


ALTER TABLE hris.vw_unit_list_asli OWNER TO postgres;

--
-- TOC entry 718 (class 1259 OID 994276)
-- Name: vw_unit_list_bak; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.vw_unit_list_bak AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(32) AS "ESELON_1",
    (x.eselon[2])::character varying(32) AS "ESELON_2",
    (x.eselon[3])::character varying(32) AS "ESELON_3",
    (x.eselon[4])::character varying(32) AS "ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WHERE (uk."EXPIRED_DATE" IS NULL)
  WITH NO DATA;


ALTER TABLE hris.vw_unit_list_bak OWNER TO postgres;

--
-- TOC entry 723 (class 1259 OID 1025245)
-- Name: vw_unit_list_bak2; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.vw_unit_list_bak2 AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(32) AS "ESELON_1",
    (x.eselon[2])::character varying(32) AS "ESELON_2",
    (x.eselon[3])::character varying(32) AS "ESELON_3",
    (x.eselon[4])::character varying(32) AS "ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WHERE (uk."EXPIRED_DATE" IS NULL)
  WITH NO DATA;


ALTER TABLE hris.vw_unit_list_bak2 OWNER TO postgres;

--
-- TOC entry 867 (class 1259 OID 2427033)
-- Name: vw_unit_list_new; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unit_list_new AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(36) AS "ESELON_1",
    (x.eselon[2])::character varying(36) AS "ESELON_2",
    (x.eselon[3])::character varying(36) AS "ESELON_3",
    (x.eselon[4])::character varying(36) AS "ESELON_4",
    uk."JENIS_SATKER",
    es1."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1",
    es2."NAMA_UNOR" AS "NAMA_UNOR_ESELON_2",
    es3."NAMA_UNOR" AS "NAMA_UNOR_ESELON_3",
    es4."NAMA_UNOR" AS "NAMA_UNOR_ESELON_4",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WHERE (uk."EXPIRED_DATE" IS NULL);


ALTER TABLE hris.vw_unit_list_new OWNER TO postgres;

--
-- TOC entry 739 (class 1259 OID 1581801)
-- Name: vw_unit_list_pejabat; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unit_list_pejabat AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    uk."PERATURAN",
    (x.eselon[1])::character varying(32) AS "ESELON_1",
    (x.eselon[2])::character varying(32) AS "ESELON_2",
    (x.eselon[3])::character varying(32) AS "ESELON_3",
    (x.eselon[4])::character varying(32) AS "ESELON_4",
    uk."JENIS_SATKER",
    x."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN",
    p."NIP_BARU",
    p."GELAR_DEPAN",
    p."NAMA" AS "PEJABAT_NAMA",
    p."GELAR_BELAKANG"
   FROM ((hris.unitkerja uk
     LEFT JOIN hris.pegawai p ON (((p."PNS_ID")::text = (uk."PEMIMPIN_PNS_ID")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    (((a."NAMA_UNOR")::text || ' - '::text) || r_1."NAMA_UNOR"),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            r."NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)));


ALTER TABLE hris.vw_unit_list_pejabat OWNER TO postgres;

--
-- TOC entry 857 (class 1259 OID 2350323)
-- Name: vw_unit_list_penyajian_data; Type: MATERIALIZED VIEW; Schema: hris; Owner: postgres
--

CREATE MATERIALIZED VIEW hris.vw_unit_list_penyajian_data AS
 SELECT uk."NO",
    uk."KODE_INTERNAL",
    uk."ID",
    uk."NAMA_UNOR",
    uk."ESELON_ID",
    uk."CEPAT_KODE",
    uk."NAMA_JABATAN",
    uk."NAMA_PEJABAT",
    uk."DIATASAN_ID",
    uk."INSTANSI_ID",
    uk."PEMIMPIN_NON_PNS_ID",
    uk."PEMIMPIN_PNS_ID",
    uk."JENIS_UNOR_ID",
    uk."UNOR_INDUK",
    uk."JUMLAH_IDEAL_STAFF",
    uk."ORDER",
    uk.deleted,
    uk."IS_SATKER",
    uk."EXPIRED_DATE",
    (x.eselon[1])::character varying(500) AS "ESELON_1",
    (x.eselon[2])::character varying(500) AS "ESELON_2",
    (x.eselon[3])::character varying(500) AS "ESELON_3",
    (x.eselon[4])::character varying(500) AS "ESELON_4",
    uk."JENIS_SATKER",
    (x."NAMA_UNOR"[1])::character varying(500) AS "NAMA_UNOR_ESELON_1",
    (x."NAMA_UNOR"[2])::character varying(500) AS "NAMA_UNOR_ESELON_2",
    (x."NAMA_UNOR"[3])::character varying(500) AS "NAMA_UNOR_ESELON_3",
    (x."NAMA_UNOR"[4])::character varying(500) AS "NAMA_UNOR_ESELON_4",
    uk."NAMA_UNOR" AS "NAMA_UNOR_FULL",
    uk."UNOR_INDUK_PENYETARAAN",
    uk."ABBREVIATION"
   FROM (((((hris.unitkerja uk
     LEFT JOIN hris.unitkerja es1 ON (((es1."ID")::text = (uk."ESELON_1")::text)))
     LEFT JOIN hris.unitkerja es2 ON (((es2."ID")::text = (uk."ESELON_2")::text)))
     LEFT JOIN hris.unitkerja es3 ON (((es3."ID")::text = (uk."ESELON_3")::text)))
     LEFT JOIN hris.unitkerja es4 ON (((es4."ID")::text = (uk."ESELON_4")::text)))
     LEFT JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    (unitkerja."NAMA_UNOR")::text AS "NAMA_UNOR",
                    (unitkerja."ID")::text AS arr_id
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a."ID",
                    ((r_1."NAMA_UNOR" || '#'::text) || (a."NAMA_UNOR")::text),
                    ((r_1.arr_id || '#'::text) || (a."ID")::text)
                   FROM (hris.unitkerja a
                     JOIN r r_1 ON (((r_1."ID")::text = (a."DIATASAN_ID")::text)))
                )
         SELECT r."ID",
            string_to_array(r."NAMA_UNOR", '#'::text) AS "NAMA_UNOR",
            string_to_array(r.arr_id, '#'::text) AS eselon
           FROM r) x ON (((uk."ID")::text = (x."ID")::text)))
  WHERE (uk."EXPIRED_DATE" IS NULL)
  WITH NO DATA;


ALTER TABLE hris.vw_unit_list_penyajian_data OWNER TO postgres;

--
-- TOC entry 510 (class 1259 OID 36445)
-- Name: vw_unor_satker_copy1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker_copy1 AS
 SELECT a."ID" AS "ID_UNOR",
    b."ID" AS "ID_SATKER",
    a."NAMA_UNOR",
    b."NAMA_UNOR" AS "NAMA_SATKER",
    c."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
   FROM ((hris.unitkerja_1234 a
     JOIN hris.unitkerja_1234 b ON (((b."ID")::text = (a."UNOR_INDUK")::text)))
     JOIN hris.unitkerja_1234 c ON (((a."ESELON_1")::text = (c."ID")::text)))
  WHERE ((a."UNOR_INDUK")::text IN ( SELECT unitkerja."ID"
           FROM hris.unitkerja_1234 unitkerja
          WHERE (unitkerja."IS_SATKER" = (1)::smallint)))
UNION ALL
 SELECT a."ID" AS "ID_UNOR",
    a."ID" AS "ID_SATKER",
    a."NAMA_UNOR",
    a."NAMA_UNOR" AS "NAMA_SATKER",
    b."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
   FROM (hris.unitkerja_1234 a
     JOIN hris.unitkerja_1234 b ON (((b."ID")::text = (a."UNOR_INDUK")::text)))
  WHERE (a."IS_SATKER" = (1)::smallint);


ALTER TABLE hris.vw_unor_satker_copy1 OWNER TO postgres;

--
-- TOC entry 6360 (class 0 OID 0)
-- Dependencies: 510
-- Name: VIEW vw_unor_satker_copy1; Type: COMMENT; Schema: hris; Owner: postgres
--

COMMENT ON VIEW hris.vw_unor_satker_copy1 IS 'Untuk Melihat Daftar Unit Kerja Berdasarkan Satkernya';


--
-- TOC entry 731 (class 1259 OID 1470455)
-- Name: vw_unor_satker_only_satker; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker_only_satker AS
 SELECT a."ID" AS "ID_UNOR",
    a."UNOR_INDUK" AS "ID_SATKER",
    a."NAMA_UNOR",
    b."NAMA_UNOR" AS "NAMA_SATKER",
    c."NAMA_UNOR_ESELON_1",
    a."EXPIRED_DATE",
    c.id_eselon_1 AS "ID_ESELON_1"
   FROM ((hris.unitkerja a
     JOIN hris.unitkerja b ON (((a."UNOR_INDUK")::text = (b."ID")::text)))
     JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    unitkerja."ID" AS id_eselon_1,
                    unitkerja."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a_1."ID",
                    r_1.id_eselon_1,
                    r_1."NAMA_UNOR_ESELON_1"
                   FROM (hris.unitkerja a_1
                     JOIN r r_1 ON (((a_1."DIATASAN_ID")::text = (r_1."ID")::text)))
                )
         SELECT r."ID",
            r.id_eselon_1,
            r."NAMA_UNOR_ESELON_1"
           FROM r) c ON (((a."ID")::text = (c."ID")::text)))
  WHERE ((a."IS_SATKER" = 1) AND (a."EXPIRED_DATE" IS NULL));


ALTER TABLE hris.vw_unor_satker_only_satker OWNER TO postgres;

--
-- TOC entry 865 (class 1259 OID 2426996)
-- Name: vw_unor_satker_satyalencana; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker_satyalencana AS
 SELECT a."ID" AS "ID_UNOR",
    a."UNOR_INDUK" AS "ID_SATKER",
    a."NAMA_UNOR",
    b."NAMA_UNOR" AS "NAMA_SATKER",
    c."NAMA_UNOR_ESELON_1",
    a."EXPIRED_DATE",
    c.id_eselon_1 AS "ID_ESELON_1"
   FROM ((hris.unitkerja a
     JOIN hris.unitkerja b ON (((a."UNOR_INDUK")::text = (b."ID")::text)))
     JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    unitkerja."ID" AS id_eselon_1,
                    unitkerja."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a_1."ID",
                    r_1.id_eselon_1,
                    r_1."NAMA_UNOR_ESELON_1"
                   FROM (hris.unitkerja a_1
                     JOIN r r_1 ON (((a_1."DIATASAN_ID")::text = (r_1."ID")::text)))
                )
         SELECT r."ID",
            r.id_eselon_1,
            r."NAMA_UNOR_ESELON_1"
           FROM r) c ON (((a."ID")::text = (c."ID")::text)));


ALTER TABLE hris.vw_unor_satker_satyalencana OWNER TO postgres;

--
-- TOC entry 511 (class 1259 OID 36450)
-- Name: vw_unor_satker_w_eselonid; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker_w_eselonid AS
 SELECT a."ID" AS "ID_UNOR",
    a."UNOR_INDUK" AS "ID_SATKER",
    a."NAMA_UNOR",
    b."NAMA_UNOR" AS "NAMA_SATKER",
    c."NAMA_UNOR_ESELON_1",
    a."ESELON_ID",
    btrim((a."NAMA_JABATAN")::text) AS "NAMA_JABATAN",
    a."DIATASAN_ID",
    c."ID" AS "ESELON_1_ID"
   FROM ((hris.unitkerja a
     JOIN hris.unitkerja b ON (((a."UNOR_INDUK")::text = (b."ID")::text)))
     JOIN ( WITH RECURSIVE r AS (
                 SELECT unitkerja."ID",
                    unitkerja."ID" AS id_eselon_1,
                    unitkerja."NAMA_UNOR" AS "NAMA_UNOR_ESELON_1"
                   FROM hris.unitkerja
                  WHERE ((unitkerja."DIATASAN_ID")::text = 'A8ACA7397AEB3912E040640A040269BB'::text)
                UNION ALL
                 SELECT a_1."ID",
                    r_1.id_eselon_1,
                    r_1."NAMA_UNOR_ESELON_1"
                   FROM (hris.unitkerja a_1
                     JOIN r r_1 ON (((a_1."DIATASAN_ID")::text = (r_1."ID")::text)))
                )
         SELECT r."ID",
            r.id_eselon_1,
            r."NAMA_UNOR_ESELON_1"
           FROM r) c ON (((a."ID")::text = (c."ID")::text)));


ALTER TABLE hris.vw_unor_satker_w_eselonid OWNER TO postgres;

--
-- TOC entry 858 (class 1259 OID 2350331)
-- Name: vw_unor_satker_w_id_eselon1; Type: VIEW; Schema: hris; Owner: postgres
--

CREATE VIEW hris.vw_unor_satker_w_id_eselon1 AS
 SELECT t."ID" AS "ID_UNOR",
    t."NAMA_UNOR",
    t."ABBREVIATION",
    t."EXPIRED_DATE",
        CASE
            WHEN (btrim((t."NAMA_UNOR")::text) = 'KEMENTERIAN PENDIDIKAN dan KEBUDAYAAN'::text) THEN t."ID"
            WHEN ((t."ESELON_2" IS NULL) OR (btrim((t."ESELON_2")::text) = ''::text)) THEN t."ESELON_1"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'universitas_dikti'::text) THEN t."ESELON_2"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Politeknik Vokasi'::text) THEN t."ESELON_2"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Multimedia Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Televisi Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Radio Pendidikan dan Kebudayaan'::text)) THEN t."ESELON_3"
            ELSE t."ESELON_2"
        END AS "ID_SATKER",
        CASE
            WHEN (btrim((t."NAMA_UNOR")::text) = 'KEMENTERIAN PENDIDIKAN dan KEBUDAYAAN'::text) THEN t."NAMA_UNOR"
            WHEN ((t."ESELON_2" IS NULL) OR (btrim((t."ESELON_2")::text) = ''::text)) THEN t."NAMA_UNOR_ESELON_1"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'universitas_dikti'::text) THEN t."NAMA_UNOR_ESELON_2"
            WHEN (btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Politeknik Vokasi'::text) THEN t."NAMA_UNOR_ESELON_2"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Multimedia Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Televisi Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            WHEN ((btrim((t."NAMA_UNOR_ESELON_1")::text) = 'Sekretariat Jenderal'::text) AND (btrim((t."NAMA_UNOR_ESELON_2")::text) = 'Pusat Data dan Teknologi Informasi'::text) AND (btrim((t."NAMA_UNOR_ESELON_3")::text) = 'Balai Pengembangan Media Radio Pendidikan dan Kebudayaan'::text)) THEN t."NAMA_UNOR_ESELON_3"
            ELSE t."NAMA_UNOR_ESELON_2"
        END AS "NAMA_SATKER",
        CASE
            WHEN (btrim((t."NAMA_UNOR")::text) = 'KEMENTERIAN PENDIDIKAN dan KEBUDAYAAN'::text) THEN t."ID"
            ELSE t."ESELON_1"
        END AS "ID_ESELON_1",
        CASE
            WHEN (btrim((t."NAMA_UNOR")::text) = 'KEMENTERIAN PENDIDIKAN dan KEBUDAYAAN'::text) THEN t."NAMA_UNOR"
            ELSE t."NAMA_UNOR_ESELON_1"
        END AS "NAMA_UNOR_ESELON_1"
   FROM hris.vw_unit_list_penyajian_data t;


ALTER TABLE hris.vw_unor_satker_w_id_eselon1 OWNER TO postgres;

--
-- TOC entry 512 (class 1259 OID 36455)
-- Name: wage; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.wage (
    "GOLONGAN" character varying(2) NOT NULL,
    "WORKING_PERIOD" smallint NOT NULL,
    "BASIC" integer,
    "TSP" integer,
    "ETC" integer
);


ALTER TABLE hris.wage OWNER TO postgres;

--
-- TOC entry 513 (class 1259 OID 36458)
-- Name: wage_2018; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.wage_2018 (
    "GOLONGAN" character varying(2) NOT NULL,
    "WORKING_PERIOD" smallint NOT NULL,
    "BASIC" integer,
    "TSP" integer,
    "ETC" integer
);


ALTER TABLE hris.wage_2018 OWNER TO postgres;

--
-- TOC entry 741 (class 1259 OID 1636262)
-- Name: z_temp_data_anomali_2023; Type: TABLE; Schema: hris; Owner: postgres
--

CREATE TABLE hris.z_temp_data_anomali_2023 (
    id bigint NOT NULL,
    kanreg_id character varying,
    instansi_kd character varying,
    instansi_nm character varying,
    anomali character varying,
    nip_baru character varying,
    nama character varying,
    nama_unor character varying,
    jabatan_fungsional character varying,
    jabatan_fungsional_umum character varying,
    jenis_jabatan character varying,
    nama_jabatan character varying,
    id_jabatan_bkn character varying,
    unit_kerja character varying,
    id_unit_kerja character varying,
    pns_id character varying,
    synced integer,
    updateddate timestamp without time zone DEFAULT now(),
    xxxx character varying
);


ALTER TABLE hris.z_temp_data_anomali_2023 OWNER TO postgres;

--
-- TOC entry 740 (class 1259 OID 1636260)
-- Name: z_temp_data_anomali_2023_id_seq; Type: SEQUENCE; Schema: hris; Owner: postgres
--

CREATE SEQUENCE hris.z_temp_data_anomali_2023_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hris.z_temp_data_anomali_2023_id_seq OWNER TO postgres;

--
-- TOC entry 6366 (class 0 OID 0)
-- Dependencies: 740
-- Name: z_temp_data_anomali_2023_id_seq; Type: SEQUENCE OWNED BY; Schema: hris; Owner: postgres
--

ALTER SEQUENCE hris.z_temp_data_anomali_2023_id_seq OWNED BY hris.z_temp_data_anomali_2023.id;


--
-- TOC entry 591 (class 1259 OID 36864)
-- Name: api_access; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_access (
    date_created timestamp without time zone DEFAULT now() NOT NULL,
    date_modified timestamp without time zone DEFAULT now() NOT NULL,
    app_id bigint,
    controller_id bigint NOT NULL
);


ALTER TABLE webservice.api_access OWNER TO postgres;

--
-- TOC entry 592 (class 1259 OID 36869)
-- Name: ws_api_controllers_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_api_controllers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_api_controllers_id_seq OWNER TO postgres;

--
-- TOC entry 593 (class 1259 OID 36871)
-- Name: api_controllers; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_controllers (
    id bigint DEFAULT nextval('webservice.ws_api_controllers_id_seq'::regclass) NOT NULL,
    name character varying(100),
    url character varying(100),
    description text,
    active smallint DEFAULT 1,
    scope smallint DEFAULT 1,
    method character varying(255),
    parameter text,
    example_param text,
    result text,
    kategori_id integer
);


ALTER TABLE webservice.api_controllers OWNER TO postgres;

--
-- TOC entry 6367 (class 0 OID 0)
-- Dependencies: 593
-- Name: COLUMN api_controllers.scope; Type: COMMENT; Schema: webservice; Owner: postgres
--

COMMENT ON COLUMN webservice.api_controllers.scope IS '1=private,2=public';


--
-- TOC entry 594 (class 1259 OID 36880)
-- Name: api_kategori; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_kategori (
    id smallint NOT NULL,
    nama_kategori character varying(1000),
    "desc" text,
    image character varying(255)
);


ALTER TABLE webservice.api_kategori OWNER TO postgres;

--
-- TOC entry 595 (class 1259 OID 36886)
-- Name: api_kategori_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.api_kategori_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.api_kategori_id_seq OWNER TO postgres;

--
-- TOC entry 6368 (class 0 OID 0)
-- Dependencies: 595
-- Name: api_kategori_id_seq; Type: SEQUENCE OWNED BY; Schema: webservice; Owner: postgres
--

ALTER SEQUENCE webservice.api_kategori_id_seq OWNED BY webservice.api_kategori.id;


--
-- TOC entry 596 (class 1259 OID 36888)
-- Name: ws_api_keys_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_api_keys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_api_keys_id_seq OWNER TO postgres;

--
-- TOC entry 597 (class 1259 OID 36890)
-- Name: api_keys; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_keys (
    id integer DEFAULT nextval('webservice.ws_api_keys_id_seq'::regclass) NOT NULL,
    app_name character varying(100),
    key character varying(40) NOT NULL,
    level integer NOT NULL,
    ignore_limits smallint NOT NULL,
    date_created integer NOT NULL,
    satker_auth text,
    active smallint DEFAULT 1
);


ALTER TABLE webservice.api_keys OWNER TO postgres;

--
-- TOC entry 598 (class 1259 OID 36898)
-- Name: ws_api_limits_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_api_limits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_api_limits_id_seq OWNER TO postgres;

--
-- TOC entry 599 (class 1259 OID 36900)
-- Name: api_limits; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_limits (
    id integer DEFAULT nextval('webservice.ws_api_limits_id_seq'::regclass) NOT NULL,
    uri character varying(255) NOT NULL,
    count integer NOT NULL,
    hour_started integer NOT NULL,
    api_key character varying(40) NOT NULL
);


ALTER TABLE webservice.api_limits OWNER TO postgres;

--
-- TOC entry 600 (class 1259 OID 36904)
-- Name: ws_api_logs_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_api_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_api_logs_id_seq OWNER TO postgres;

--
-- TOC entry 601 (class 1259 OID 36906)
-- Name: api_logs; Type: TABLE; Schema: webservice; Owner: postgres
--

CREATE TABLE webservice.api_logs (
    id bigint DEFAULT nextval('webservice.ws_api_logs_id_seq'::regclass) NOT NULL,
    uri character varying(255) NOT NULL,
    method character varying(6) NOT NULL,
    params text,
    api_key character varying(40) NOT NULL,
    ip_address character varying(45) NOT NULL,
    "time" integer NOT NULL,
    rtime real,
    authorized character varying(1) NOT NULL,
    response_code smallint
);


ALTER TABLE webservice.api_logs OWNER TO postgres;

--
-- TOC entry 602 (class 1259 OID 36913)
-- Name: ws_api_access_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_api_access_id_seq
    START WITH 13
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_api_access_id_seq OWNER TO postgres;

--
-- TOC entry 603 (class 1259 OID 36915)
-- Name: ws_fn_id_seq; Type: SEQUENCE; Schema: webservice; Owner: postgres
--

CREATE SEQUENCE webservice.ws_fn_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE webservice.ws_fn_id_seq OWNER TO postgres;

--
-- TOC entry 5315 (class 2604 OID 264124)
-- Name: absen ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.absen ALTER COLUMN "ID" SET DEFAULT nextval('hris."absen_ID_seq"'::regclass);


--
-- TOC entry 5185 (class 2604 OID 819349)
-- Name: anak ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.anak ALTER COLUMN "ID" SET DEFAULT nextval('hris."anak_ID_seq"'::regclass);


--
-- TOC entry 5300 (class 2604 OID 213014)
-- Name: arsip ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.arsip ALTER COLUMN "ID" SET DEFAULT nextval('hris."arsip_ID_seq"'::regclass);


--
-- TOC entry 5346 (class 2604 OID 2425316)
-- Name: asesmen_hasil_asesmen id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_hasil_asesmen ALTER COLUMN id SET DEFAULT nextval('hris.asesmen_hasil_asesmen_id_seq'::regclass);


--
-- TOC entry 5345 (class 2604 OID 2425300)
-- Name: asesmen_pegawai_berpotensi_jpt id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_pegawai_berpotensi_jpt ALTER COLUMN id SET DEFAULT nextval('hris.asesmen_pegawai_berpotensi_jpt_id_seq'::regclass);


--
-- TOC entry 5344 (class 2604 OID 2425289)
-- Name: asesmen_riwayat_hukuman_disiplin id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_riwayat_hukuman_disiplin ALTER COLUMN id SET DEFAULT nextval('hris.asesmen_riwayat_hukuman_disiplin_id_seq'::regclass);


--
-- TOC entry 5186 (class 2604 OID 36918)
-- Name: baperjakat ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.baperjakat ALTER COLUMN "ID" SET DEFAULT nextval('hris."baperjakat_ID_seq"'::regclass);


--
-- TOC entry 5187 (class 2604 OID 36919)
-- Name: daftar_rohaniawan id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.daftar_rohaniawan ALTER COLUMN id SET DEFAULT nextval('hris.daftar_rohaniawan_id_seq'::regclass);


--
-- TOC entry 5188 (class 2604 OID 36920)
-- Name: email_queue id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.email_queue ALTER COLUMN id SET DEFAULT nextval('hris.email_queue_id_seq'::regclass);


--
-- TOC entry 5318 (class 2604 OID 265310)
-- Name: hari_libur ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.hari_libur ALTER COLUMN "ID" SET DEFAULT nextval('hris."hari_libur_ID_seq"'::regclass);


--
-- TOC entry 5189 (class 2604 OID 1095624)
-- Name: istri ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.istri ALTER COLUMN "ID" SET DEFAULT nextval('hris."istri_ID_seq"'::regclass);


--
-- TOC entry 5290 (class 2604 OID 47718)
-- Name: izin ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin ALTER COLUMN "ID" SET DEFAULT nextval('hris."izin_ID_seq"'::regclass);


--
-- TOC entry 5319 (class 2604 OID 265316)
-- Name: izin_alasan ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin_alasan ALTER COLUMN "ID" SET DEFAULT nextval('hris."izin_alasan_ID_seq"'::regclass);


--
-- TOC entry 5320 (class 2604 OID 282506)
-- Name: izin_verifikasi ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin_verifikasi ALTER COLUMN "ID" SET DEFAULT nextval('hris."izin_verifikasi_ID_seq"'::regclass);


--
-- TOC entry 5161 (class 2604 OID 36922)
-- Name: jabatan id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jabatan ALTER COLUMN id SET DEFAULT nextval('hris.jabatan_id_seq'::regclass);


--
-- TOC entry 5303 (class 2604 OID 213023)
-- Name: jenis_arsip ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_arsip ALTER COLUMN "ID" SET DEFAULT nextval('hris.jenis_arsip_id_seq'::regclass);


--
-- TOC entry 5336 (class 2604 OID 1844211)
-- Name: jenis_diklat id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_diklat ALTER COLUMN id SET DEFAULT nextval('hris.jenis_diklat_id_seq'::regclass);


--
-- TOC entry 5294 (class 2604 OID 47727)
-- Name: jenis_izin ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_izin ALTER COLUMN "ID" SET DEFAULT nextval('hris."jenis_izin_ID_seq"'::regclass);


--
-- TOC entry 5338 (class 2604 OID 1844218)
-- Name: jenis_kursus id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_kursus ALTER COLUMN id SET DEFAULT nextval('hris.jenis_kursus_id_seq'::regclass);


--
-- TOC entry 5192 (class 2604 OID 36923)
-- Name: kandidat_baperjakat ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kandidat_baperjakat ALTER COLUMN "ID" SET DEFAULT nextval('hris."kandidat_baperjakat_ID_seq"'::regclass);


--
-- TOC entry 5193 (class 2604 OID 36924)
-- Name: kategori_ds id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kategori_ds ALTER COLUMN id SET DEFAULT nextval('hris.kategori_ds_id_seq'::regclass);


--
-- TOC entry 5304 (class 2604 OID 249375)
-- Name: kategori_jenis_arsip ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kategori_jenis_arsip ALTER COLUMN "ID" SET DEFAULT nextval('hris."kategori_jenis_arsip_ID_seq"'::regclass);


--
-- TOC entry 5198 (class 2604 OID 36925)
-- Name: layanan id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan ALTER COLUMN id SET DEFAULT nextval('hris.layanan_id_seq1'::regclass);


--
-- TOC entry 5199 (class 2604 OID 36926)
-- Name: layanan_tipe id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan_tipe ALTER COLUMN id SET DEFAULT nextval('hris.layanan_id_seq'::regclass);


--
-- TOC entry 5200 (class 2604 OID 36927)
-- Name: layanan_usulan id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan_usulan ALTER COLUMN id SET DEFAULT nextval('hris.layanan_usulan_id_seq'::regclass);


--
-- TOC entry 5317 (class 2604 OID 264131)
-- Name: line_approval_izin ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.line_approval_izin ALTER COLUMN "ID" SET DEFAULT nextval('hris."line_approval_izin_ID_seq"'::regclass);


--
-- TOC entry 5201 (class 2604 OID 36928)
-- Name: log_ds ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.log_ds ALTER COLUMN "ID" SET DEFAULT nextval('hris."log_ds_ID_seq"'::regclass);


--
-- TOC entry 5339 (class 2604 OID 1844227)
-- Name: log_request id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.log_request ALTER COLUMN id SET DEFAULT nextval('hris.log_request_id_seq'::regclass);


--
-- TOC entry 5203 (class 2604 OID 36929)
-- Name: log_transaksi ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.log_transaksi ALTER COLUMN "ID" SET DEFAULT nextval('hris."log_transaksi_ID_seq"'::regclass);


--
-- TOC entry 5322 (class 2604 OID 522563)
-- Name: mst_jenis_satker id_jenis; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_jenis_satker ALTER COLUMN id_jenis SET DEFAULT nextval('hris.jenis_satker_id_jenis_seq'::regclass);


--
-- TOC entry 5323 (class 2604 OID 522569)
-- Name: mst_peraturan_otk id_peraturan; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_peraturan_otk ALTER COLUMN id_peraturan SET DEFAULT nextval('hris.peraturan_otk_id_peraturan_seq'::regclass);


--
-- TOC entry 5205 (class 2604 OID 36930)
-- Name: mst_templates id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_templates ALTER COLUMN id SET DEFAULT nextval('hris.mst_templates_id_seq'::regclass);


--
-- TOC entry 5206 (class 2604 OID 36931)
-- Name: nip_pejabat id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.nip_pejabat ALTER COLUMN id SET DEFAULT nextval('hris.nip_pejabat_id_seq'::regclass);


--
-- TOC entry 5207 (class 2604 OID 36932)
-- Name: orang_tua ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.orang_tua ALTER COLUMN "ID" SET DEFAULT nextval('hris."orang_tua_ID_seq"'::regclass);


--
-- TOC entry 5297 (class 2604 OID 47733)
-- Name: pegawai_atasan ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pegawai_atasan ALTER COLUMN "ID" SET DEFAULT nextval('hris."pegawai_atasan_ID_seq"'::regclass);


--
-- TOC entry 5216 (class 2604 OID 36933)
-- Name: pengajuan_tubel ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pengajuan_tubel ALTER COLUMN "ID" SET DEFAULT nextval('hris."pengajuan_tubel_ID_seq"'::regclass);


--
-- TOC entry 5217 (class 2604 OID 36934)
-- Name: perkiraan_kpo id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.perkiraan_kpo ALTER COLUMN id SET DEFAULT nextval('hris.perkiraan_kpo_id_seq'::regclass);


--
-- TOC entry 5221 (class 2604 OID 36935)
-- Name: perkiraan_usulan_log id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.perkiraan_usulan_log ALTER COLUMN id SET DEFAULT nextval('hris.perkiraan_usulan_log_id_seq'::regclass);


--
-- TOC entry 5324 (class 2604 OID 522575)
-- Name: peta_jabatan_permen id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.peta_jabatan_permen ALTER COLUMN id SET DEFAULT nextval('hris.peta_jabatan_permen_id_seq'::regclass);


--
-- TOC entry 5223 (class 2604 OID 36936)
-- Name: pindah_unit ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pindah_unit ALTER COLUMN "ID" SET DEFAULT nextval('hris."pindah_unit_ID_seq"'::regclass);


--
-- TOC entry 5224 (class 2604 OID 36937)
-- Name: ref_tunjangan_kinerja ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.ref_tunjangan_kinerja ALTER COLUMN "ID" SET DEFAULT nextval('hris."ref_tunjangan_kinerja_ID_seq"'::regclass);


--
-- TOC entry 5325 (class 2604 OID 522581)
-- Name: request_formasi id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.request_formasi ALTER COLUMN id SET DEFAULT nextval('hris.request_formasi_id_seq'::regclass);


--
-- TOC entry 5225 (class 2604 OID 36938)
-- Name: role_permissions id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.role_permissions ALTER COLUMN id SET DEFAULT nextval('hris.role_permissions_id_seq'::regclass);


--
-- TOC entry 5233 (class 2604 OID 407344)
-- Name: roles_users role_user_id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.roles_users ALTER COLUMN role_user_id SET DEFAULT nextval('hris.roles_users_role_user_id_seq'::regclass);


--
-- TOC entry 5234 (class 2604 OID 36939)
-- Name: rpt_golongan_bulan ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_golongan_bulan ALTER COLUMN "ID" SET DEFAULT nextval('hris."rpt_golongan_bulan_ID_seq"'::regclass);


--
-- TOC entry 5235 (class 2604 OID 36940)
-- Name: rpt_jumlah_asn ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_jumlah_asn ALTER COLUMN "ID" SET DEFAULT nextval('hris."rpt_jumlah_asn_ID_seq"'::regclass);


--
-- TOC entry 5236 (class 2604 OID 36941)
-- Name: rpt_pendidikan_bulan ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_pendidikan_bulan ALTER COLUMN "ID" SET DEFAULT nextval('hris."rpt_pendidikan_bulan_ID_seq"'::regclass);


--
-- TOC entry 5237 (class 2604 OID 36942)
-- Name: rwt_assesmen ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_assesmen ALTER COLUMN "ID" SET DEFAULT nextval('hris."rwt_assesmen_ID_seq"'::regclass);


--
-- TOC entry 5341 (class 2604 OID 1844382)
-- Name: rwt_diklat id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_diklat ALTER COLUMN id SET DEFAULT nextval('hris.rwt_diklat_id_seq'::regclass);


--
-- TOC entry 5239 (class 2604 OID 36943)
-- Name: rwt_hukdis ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_hukdis ALTER COLUMN "ID" SET DEFAULT nextval('hris."rwt_hukdis_ID_seq"'::regclass);


--
-- TOC entry 5241 (class 2604 OID 36944)
-- Name: rwt_kgb id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_kgb ALTER COLUMN id SET DEFAULT nextval('hris.rwt_kgb_id_seq'::regclass);


--
-- TOC entry 5330 (class 2604 OID 1512866)
-- Name: rwt_kinerja id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_kinerja ALTER COLUMN id SET DEFAULT nextval('hris.rwt_kinerja_id_seq'::regclass);


--
-- TOC entry 5242 (class 2604 OID 36945)
-- Name: rwt_kursus ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_kursus ALTER COLUMN "ID" SET DEFAULT nextval('hris."rwt_kursus_ID_seq"'::regclass);


--
-- TOC entry 5181 (class 2604 OID 36946)
-- Name: rwt_nine_box ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_nine_box ALTER COLUMN "ID" SET DEFAULT nextval('hris."NINE_BOX_ID_seq"'::regclass);


--
-- TOC entry 5352 (class 2604 OID 2514294)
-- Name: rwt_penghargaan_umum id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_penghargaan_umum ALTER COLUMN id SET DEFAULT nextval('hris.rwt_penghargaan_umum_id_seq'::regclass);


--
-- TOC entry 5349 (class 2604 OID 2514272)
-- Name: rwt_penugasan id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_penugasan ALTER COLUMN id SET DEFAULT nextval('hris.rwt_penugasan_id_seq'::regclass);


--
-- TOC entry 5289 (class 2604 OID 47187)
-- Name: rwt_pns_cpns ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_pns_cpns ALTER COLUMN "ID" SET DEFAULT nextval('hris."rwt_pns_cpns_ID_seq"'::regclass);


--
-- TOC entry 5246 (class 2604 OID 36947)
-- Name: rwt_tugas_belajar ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_tugas_belajar ALTER COLUMN "ID" SET DEFAULT nextval('hris."rwt_tugas_belajar_ID_seq"'::regclass);


--
-- TOC entry 5347 (class 2604 OID 2502453)
-- Name: rwt_ujikom id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_ujikom ALTER COLUMN id SET DEFAULT nextval('hris.rwt_ujikom_id_seq'::regclass);


--
-- TOC entry 5248 (class 2604 OID 36948)
-- Name: schema_version id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.schema_version ALTER COLUMN id SET DEFAULT nextval('hris.schema_version_id_seq'::regclass);


--
-- TOC entry 5249 (class 2604 OID 36949)
-- Name: settings id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.settings ALTER COLUMN id SET DEFAULT nextval('hris.settings_id_seq'::regclass);


--
-- TOC entry 5298 (class 2604 OID 47742)
-- Name: sisa_cuti ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.sisa_cuti ALTER COLUMN "ID" SET DEFAULT nextval('hris."sisa_cuti_ID_seq"'::regclass);


--
-- TOC entry 5329 (class 2604 OID 1161010)
-- Name: synch_jumlah_pegawai id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.synch_jumlah_pegawai ALTER COLUMN id SET DEFAULT nextval('hris.synch_jumlah_pegawai_id_seq'::regclass);


--
-- TOC entry 5250 (class 2604 OID 36950)
-- Name: tbl_file_ds id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds ALTER COLUMN id SET DEFAULT nextval('hris.tbl_file_ds_id_seq'::regclass);


--
-- TOC entry 5255 (class 2604 OID 36951)
-- Name: tbl_file_ds_corrector id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_corrector ALTER COLUMN id SET DEFAULT nextval('hris.tbl_file_ds_corrector_id_seq'::regclass);


--
-- TOC entry 5299 (class 2604 OID 63957)
-- Name: tbl_file_ds_riwayat id_riwayat; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_riwayat ALTER COLUMN id_riwayat SET DEFAULT nextval('hris.tbl_file_ds_riwayat_id_riwayat_seq'::regclass);


--
-- TOC entry 5305 (class 2604 OID 249381)
-- Name: tte_master_korektor id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_korektor ALTER COLUMN id SET DEFAULT nextval('hris.tte_master_korektor_id_seq'::regclass);


--
-- TOC entry 5306 (class 2604 OID 249387)
-- Name: tte_master_proses id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_proses ALTER COLUMN id SET DEFAULT nextval('hris.tte_master_proses_id_seq'::regclass);


--
-- TOC entry 5307 (class 2604 OID 249396)
-- Name: tte_master_proses_variable id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_proses_variable ALTER COLUMN id SET DEFAULT nextval('hris.tte_master_proses_variable_id_seq'::regclass);


--
-- TOC entry 5308 (class 2604 OID 249402)
-- Name: tte_master_variable id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_variable ALTER COLUMN id SET DEFAULT nextval('hris."tte_ master_variable_id_seq"'::regclass);


--
-- TOC entry 5309 (class 2604 OID 1107892)
-- Name: tte_trx_draft_sk id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_draft_sk ALTER COLUMN id SET DEFAULT nextval('hris.tte_trx_draft_sk_id_seq'::regclass);


--
-- TOC entry 5313 (class 2604 OID 249417)
-- Name: tte_trx_draft_sk_detil id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_draft_sk_detil ALTER COLUMN id SET DEFAULT nextval('hris.tte_trx_draft_sk_detil_id_seq'::regclass);


--
-- TOC entry 5314 (class 2604 OID 249423)
-- Name: tte_trx_korektor_draft id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_korektor_draft ALTER COLUMN id SET DEFAULT nextval('hris.tte_trx_korektor_draft_id_seq'::regclass);


--
-- TOC entry 5256 (class 2604 OID 36952)
-- Name: update_mandiri ID; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.update_mandiri ALTER COLUMN "ID" SET DEFAULT nextval('hris."update_mandiri_ID_seq"'::regclass);


--
-- TOC entry 5257 (class 2604 OID 36953)
-- Name: user_cookies id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.user_cookies ALTER COLUMN id SET DEFAULT nextval('hris.user_cookies_id_seq'::regclass);


--
-- TOC entry 5278 (class 2604 OID 36954)
-- Name: usulan_dokumen id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.usulan_dokumen ALTER COLUMN id SET DEFAULT nextval('hris.usulan_documents_id_seq'::regclass);


--
-- TOC entry 5333 (class 2604 OID 1636265)
-- Name: z_temp_data_anomali_2023 id; Type: DEFAULT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.z_temp_data_anomali_2023 ALTER COLUMN id SET DEFAULT nextval('hris.z_temp_data_anomali_2023_id_seq'::regclass);


--
-- TOC entry 5284 (class 2604 OID 36957)
-- Name: api_kategori id; Type: DEFAULT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_kategori ALTER COLUMN id SET DEFAULT nextval('webservice.api_kategori_id_seq'::regclass);


--
-- TOC entry 5411 (class 2606 OID 43571)
-- Name: rwt_nine_box NINE_BOX_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_nine_box
    ADD CONSTRAINT "NINE_BOX_pkey" PRIMARY KEY ("ID");


--
-- TOC entry 5574 (class 2606 OID 264127)
-- Name: absen absen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.absen
    ADD CONSTRAINT absen_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5413 (class 2606 OID 43573)
-- Name: activities activities_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (activity_id);


--
-- TOC entry 5356 (class 2606 OID 43575)
-- Name: agama agama_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.agama
    ADD CONSTRAINT agama_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5415 (class 2606 OID 819351)
-- Name: anak anak_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.anak
    ADD CONSTRAINT anak_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5554 (class 2606 OID 213019)
-- Name: arsip arsip_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.arsip
    ADD CONSTRAINT arsip_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5626 (class 2606 OID 2425321)
-- Name: asesmen_hasil_asesmen asesmen_hasil_asesmen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_hasil_asesmen
    ADD CONSTRAINT asesmen_hasil_asesmen_pkey PRIMARY KEY (id);


--
-- TOC entry 5624 (class 2606 OID 2425305)
-- Name: asesmen_pegawai_berpotensi_jpt asesmen_pegawai_berpotensi_jpt_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_pegawai_berpotensi_jpt
    ADD CONSTRAINT asesmen_pegawai_berpotensi_jpt_pkey PRIMARY KEY (id);


--
-- TOC entry 5622 (class 2606 OID 2425294)
-- Name: asesmen_riwayat_hukuman_disiplin asesmen_riwayat_hukuman_disiplin_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.asesmen_riwayat_hukuman_disiplin
    ADD CONSTRAINT asesmen_riwayat_hukuman_disiplin_pkey PRIMARY KEY (id);


--
-- TOC entry 5419 (class 2606 OID 43579)
-- Name: ci3_sessions ci3_sessions_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.ci3_sessions
    ADD CONSTRAINT ci3_sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5423 (class 2606 OID 43581)
-- Name: data_jabatan_tomi_11_06_2019 data_jabatan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.data_jabatan_tomi_11_06_2019
    ADD CONSTRAINT data_jabatan_pkey PRIMARY KEY ("ID_Jabatan");


--
-- TOC entry 5478 (class 2606 OID 43583)
-- Name: ref_tunjangan_jabatan data_jabatan_tunjab_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.ref_tunjangan_jabatan
    ADD CONSTRAINT data_jabatan_tunjab_pkey PRIMARY KEY ("ID_TUNJAB");


--
-- TOC entry 5425 (class 2606 OID 43585)
-- Name: email_queue email_queue_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.email_queue
    ADD CONSTRAINT email_queue_pkey PRIMARY KEY (id);


--
-- TOC entry 5358 (class 2606 OID 43587)
-- Name: golongan golongan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.golongan
    ADD CONSTRAINT golongan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5578 (class 2606 OID 265312)
-- Name: hari_libur hari_libur_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.hari_libur
    ADD CONSTRAINT hari_libur_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5427 (class 2606 OID 43589)
-- Name: instansi instansi_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.instansi
    ADD CONSTRAINT instansi_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5429 (class 2606 OID 1095626)
-- Name: istri istri_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.istri
    ADD CONSTRAINT istri_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5580 (class 2606 OID 265318)
-- Name: izin_alasan izin_alasan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin_alasan
    ADD CONSTRAINT izin_alasan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5582 (class 2606 OID 282511)
-- Name: izin_verifikasi izin_verifikasi_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin_verifikasi
    ADD CONSTRAINT izin_verifikasi_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5431 (class 2606 OID 43593)
-- Name: jabatan_copy jabatan_copy_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jabatan_copy
    ADD CONSTRAINT jabatan_copy_pkey PRIMARY KEY (id);


--
-- TOC entry 5360 (class 2606 OID 43595)
-- Name: jabatan jabatan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jabatan
    ADD CONSTRAINT jabatan_pkey PRIMARY KEY ("KODE_JABATAN");


--
-- TOC entry 5556 (class 2606 OID 213028)
-- Name: jenis_arsip jenis_arsip_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_arsip
    ADD CONSTRAINT jenis_arsip_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5362 (class 2606 OID 43597)
-- Name: jenis_diklat_fungsional jenis_diklat_fungsional_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_diklat_fungsional
    ADD CONSTRAINT jenis_diklat_fungsional_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5610 (class 2606 OID 1844214)
-- Name: jenis_diklat jenis_diklat_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_diklat
    ADD CONSTRAINT jenis_diklat_pkey PRIMARY KEY (id);


--
-- TOC entry 5616 (class 2606 OID 2122430)
-- Name: jenis_diklat_siasn jenis_diklat_siasn_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_diklat_siasn
    ADD CONSTRAINT jenis_diklat_siasn_pkey PRIMARY KEY (id);


--
-- TOC entry 5364 (class 2606 OID 43599)
-- Name: jenis_diklat_struktural jenis_diklat_struktural_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_diklat_struktural
    ADD CONSTRAINT jenis_diklat_struktural_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5366 (class 2606 OID 43601)
-- Name: jenis_jabatan jenis_jabatan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_jabatan
    ADD CONSTRAINT jenis_jabatan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5368 (class 2606 OID 43603)
-- Name: jenis_kawin jenis_kawin_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_kawin
    ADD CONSTRAINT jenis_kawin_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5433 (class 2606 OID 43605)
-- Name: jenis_kp jenis_kp_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_kp
    ADD CONSTRAINT jenis_kp_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5612 (class 2606 OID 1844223)
-- Name: jenis_kursus jenis_kursus_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_kursus
    ADD CONSTRAINT jenis_kursus_pkey PRIMARY KEY (id);


--
-- TOC entry 5370 (class 2606 OID 43607)
-- Name: jenis_pegawai jenis_pegawai_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_pegawai
    ADD CONSTRAINT jenis_pegawai_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5372 (class 2606 OID 43609)
-- Name: jenis_penghargaan jenis_penghargaan_ID; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_penghargaan
    ADD CONSTRAINT "jenis_penghargaan_ID" PRIMARY KEY ("ID");


--
-- TOC entry 5374 (class 2606 OID 43611)
-- Name: jenis_penghargaan jenis_penghargaan_NAMA; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_penghargaan
    ADD CONSTRAINT "jenis_penghargaan_NAMA" UNIQUE ("NAMA");


--
-- TOC entry 5618 (class 2606 OID 2122439)
-- Name: jenis_rumpun_diklat_siasn jenis_rumpun_diklat_siasn_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_rumpun_diklat_siasn
    ADD CONSTRAINT jenis_rumpun_diklat_siasn_pkey PRIMARY KEY (id);


--
-- TOC entry 5592 (class 2606 OID 522565)
-- Name: mst_jenis_satker jenis_satker_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_jenis_satker
    ADD CONSTRAINT jenis_satker_pkey PRIMARY KEY (id_jenis);


--
-- TOC entry 5435 (class 2606 OID 43613)
-- Name: kandidat_baperjakat kandidat_baperjakat_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kandidat_baperjakat
    ADD CONSTRAINT kandidat_baperjakat_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5437 (class 2606 OID 43615)
-- Name: kategori_ds kategori_ds_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kategori_ds
    ADD CONSTRAINT kategori_ds_pkey PRIMARY KEY (id);


--
-- TOC entry 5558 (class 2606 OID 249377)
-- Name: kategori_jenis_arsip kategori_jenis_arsip_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kategori_jenis_arsip
    ADD CONSTRAINT kategori_jenis_arsip_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5376 (class 2606 OID 43617)
-- Name: kedudukan_hukum kedudukan_hukum_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kedudukan_hukum
    ADD CONSTRAINT kedudukan_hukum_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5439 (class 2606 OID 43619)
-- Name: kpkn kpkn_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kpkn
    ADD CONSTRAINT kpkn_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5443 (class 2606 OID 43621)
-- Name: kuota_jabatan_16sep2019 kuota_jabatan_copy1_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kuota_jabatan_16sep2019
    ADD CONSTRAINT kuota_jabatan_copy1_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5441 (class 2606 OID 43623)
-- Name: kuota_jabatan_1209 kuota_jabatan_copy_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.kuota_jabatan_1209
    ADD CONSTRAINT kuota_jabatan_copy_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5447 (class 2606 OID 43627)
-- Name: layanan_tipe layanan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan_tipe
    ADD CONSTRAINT layanan_pkey PRIMARY KEY (id);


--
-- TOC entry 5445 (class 2606 OID 43629)
-- Name: layanan layanan_pkey1; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan
    ADD CONSTRAINT layanan_pkey1 PRIMARY KEY (id);


--
-- TOC entry 5449 (class 2606 OID 43631)
-- Name: layanan_usulan layanan_usulan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.layanan_usulan
    ADD CONSTRAINT layanan_usulan_pkey PRIMARY KEY (id);


--
-- TOC entry 5576 (class 2606 OID 264133)
-- Name: line_approval_izin line_approval_izin_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.line_approval_izin
    ADD CONSTRAINT line_approval_izin_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5451 (class 2606 OID 43633)
-- Name: log_ds log_ds_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.log_ds
    ADD CONSTRAINT log_ds_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5453 (class 2606 OID 43635)
-- Name: log_transaksi log_transaksi_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.log_transaksi
    ADD CONSTRAINT log_transaksi_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5455 (class 2606 OID 43637)
-- Name: login_attempts login_attempts_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- TOC entry 5378 (class 2606 OID 43639)
-- Name: lokasi lokasi_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.lokasi
    ADD CONSTRAINT lokasi_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5457 (class 2606 OID 43641)
-- Name: mst_templates mst_templates_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_templates
    ADD CONSTRAINT mst_templates_pkey PRIMARY KEY (id);


--
-- TOC entry 5459 (class 2606 OID 43643)
-- Name: orang_tua orang_tua_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.orang_tua
    ADD CONSTRAINT orang_tua_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5548 (class 2606 OID 47738)
-- Name: pegawai_atasan pegawai_atasan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pegawai_atasan
    ADD CONSTRAINT pegawai_atasan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5461 (class 2606 OID 43645)
-- Name: pegawai_bkn pegawai_bkn_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pegawai_bkn
    ADD CONSTRAINT pegawai_bkn_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5464 (class 2606 OID 43647)
-- Name: pegawai_copy pegawai_copy_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pegawai_copy
    ADD CONSTRAINT pegawai_copy_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5389 (class 2606 OID 43649)
-- Name: pegawai pegawai_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pegawai
    ADD CONSTRAINT pegawai_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5380 (class 2606 OID 43651)
-- Name: pendidikan pendidikan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pendidikan
    ADD CONSTRAINT pendidikan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5594 (class 2606 OID 522571)
-- Name: mst_peraturan_otk peraturan_otk_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.mst_peraturan_otk
    ADD CONSTRAINT peraturan_otk_pkey PRIMARY KEY (id_peraturan);


--
-- TOC entry 5470 (class 2606 OID 43653)
-- Name: perkiraan_ppo perkiraan_kpo_copy1_pkey1; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.perkiraan_ppo
    ADD CONSTRAINT perkiraan_kpo_copy1_pkey1 PRIMARY KEY (id);


--
-- TOC entry 5526 (class 2606 OID 43655)
-- Name: usulan_dokumen perkiraan_kpo_documents_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.usulan_dokumen
    ADD CONSTRAINT perkiraan_kpo_documents_pkey PRIMARY KEY (id);


--
-- TOC entry 5468 (class 2606 OID 43657)
-- Name: perkiraan_kpo perkiraan_kpo_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.perkiraan_kpo
    ADD CONSTRAINT perkiraan_kpo_pkey PRIMARY KEY (id);


--
-- TOC entry 5472 (class 2606 OID 43659)
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (permission_id);


--
-- TOC entry 5596 (class 2606 OID 522577)
-- Name: peta_jabatan_permen peta_jabatan_permen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.peta_jabatan_permen
    ADD CONSTRAINT peta_jabatan_permen_pkey PRIMARY KEY (id);


--
-- TOC entry 5417 (class 2606 OID 43661)
-- Name: baperjakat pk_baperjakat; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.baperjakat
    ADD CONSTRAINT pk_baperjakat PRIMARY KEY ("ID");


--
-- TOC entry 5421 (class 2606 OID 43663)
-- Name: daftar_rohaniawan pk_daftar_rohaniawan; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.daftar_rohaniawan
    ADD CONSTRAINT pk_daftar_rohaniawan PRIMARY KEY (id);


--
-- TOC entry 5544 (class 2606 OID 47723)
-- Name: izin pk_izin; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.izin
    ADD CONSTRAINT pk_izin PRIMARY KEY ("ID");


--
-- TOC entry 5546 (class 2606 OID 47729)
-- Name: jenis_izin pk_jenis_izin; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.jenis_izin
    ADD CONSTRAINT pk_jenis_izin PRIMARY KEY ("ID");


--
-- TOC entry 5466 (class 2606 OID 43665)
-- Name: pengajuan_tubel pk_pengajuan_tubel; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pengajuan_tubel
    ADD CONSTRAINT pk_pengajuan_tubel PRIMARY KEY ("ID");


--
-- TOC entry 5474 (class 2606 OID 43667)
-- Name: pindah_unit pk_pindah_unit; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.pindah_unit
    ADD CONSTRAINT pk_pindah_unit PRIMARY KEY ("ID");


--
-- TOC entry 5550 (class 2606 OID 47744)
-- Name: sisa_cuti pk_sisa_cuti; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.sisa_cuti
    ADD CONSTRAINT pk_sisa_cuti PRIMARY KEY ("ID");


--
-- TOC entry 5562 (class 2606 OID 249392)
-- Name: tte_master_proses pk_tte_master_proses; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_proses
    ADD CONSTRAINT pk_tte_master_proses PRIMARY KEY (id);


--
-- TOC entry 5476 (class 2606 OID 43669)
-- Name: ref_jabatan ref_jabatan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.ref_jabatan
    ADD CONSTRAINT ref_jabatan_pkey PRIMARY KEY ("ID_JABATAN");


--
-- TOC entry 5598 (class 2606 OID 522586)
-- Name: request_formasi request_formasi_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.request_formasi
    ADD CONSTRAINT request_formasi_pkey PRIMARY KEY (id);


--
-- TOC entry 5480 (class 2606 OID 43671)
-- Name: role_permissions role_permissions_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.role_permissions
    ADD CONSTRAINT role_permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 5482 (class 2606 OID 43673)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- TOC entry 5484 (class 2606 OID 43675)
-- Name: rpt_golongan_bulan rpt_golongan_bulan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_golongan_bulan
    ADD CONSTRAINT rpt_golongan_bulan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5486 (class 2606 OID 43677)
-- Name: rpt_jumlah_asn rpt_jumlah_asn_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_jumlah_asn
    ADD CONSTRAINT rpt_jumlah_asn_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5488 (class 2606 OID 43679)
-- Name: rpt_pendidikan_bulan rpt_pendidikan_bulan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rpt_pendidikan_bulan
    ADD CONSTRAINT rpt_pendidikan_bulan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5491 (class 2606 OID 43681)
-- Name: rwt_assesmen rwt_assesmen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_assesmen
    ADD CONSTRAINT rwt_assesmen_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5393 (class 2606 OID 43683)
-- Name: rwt_diklat_fungsional rwt_diklat_fungsional_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_diklat_fungsional
    ADD CONSTRAINT rwt_diklat_fungsional_pkey PRIMARY KEY ("DIKLAT_FUNGSIONAL_ID");


--
-- TOC entry 5614 (class 2606 OID 1844388)
-- Name: rwt_diklat rwt_diklat_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_diklat
    ADD CONSTRAINT rwt_diklat_pkey PRIMARY KEY (id);


--
-- TOC entry 5398 (class 2606 OID 43700)
-- Name: rwt_diklat_struktural rwt_diklat_struktural_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_diklat_struktural
    ADD CONSTRAINT rwt_diklat_struktural_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5401 (class 2606 OID 43702)
-- Name: rwt_golongan rwt_golongan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_golongan
    ADD CONSTRAINT rwt_golongan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5608 (class 2606 OID 1778192)
-- Name: rwt_jabatan_empty rwt_jabatan_copy1_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_jabatan_empty
    ADD CONSTRAINT rwt_jabatan_copy1_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5403 (class 2606 OID 43704)
-- Name: rwt_jabatan rwt_jabatan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_jabatan
    ADD CONSTRAINT rwt_jabatan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5405 (class 2606 OID 43706)
-- Name: rwt_pekerjaan rwt_pekerjaan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_pekerjaan
    ADD CONSTRAINT rwt_pekerjaan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5407 (class 2606 OID 43708)
-- Name: rwt_pendidikan rwt_pendidikan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_pendidikan
    ADD CONSTRAINT rwt_pendidikan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5494 (class 2606 OID 43710)
-- Name: rwt_penghargaan rwt_penghargaan_ID; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_penghargaan
    ADD CONSTRAINT "rwt_penghargaan_ID" PRIMARY KEY ("ID");


--
-- TOC entry 5632 (class 2606 OID 2514301)
-- Name: rwt_penghargaan_umum rwt_penghargaan_umum_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_penghargaan_umum
    ADD CONSTRAINT rwt_penghargaan_umum_pkey PRIMARY KEY (id);


--
-- TOC entry 5630 (class 2606 OID 2514279)
-- Name: rwt_penugasan rwt_penugasan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_penugasan
    ADD CONSTRAINT rwt_penugasan_pkey PRIMARY KEY (id);


--
-- TOC entry 5500 (class 2606 OID 43712)
-- Name: rwt_pindah_unit_kerja rwt_pindah_unit_kerja_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_pindah_unit_kerja
    ADD CONSTRAINT rwt_pindah_unit_kerja_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5542 (class 2606 OID 47192)
-- Name: rwt_pns_cpns rwt_pns_cpns_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_pns_cpns
    ADD CONSTRAINT rwt_pns_cpns_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5409 (class 2606 OID 43714)
-- Name: rwt_prestasi_kerja rwt_prestasi_kerja_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_prestasi_kerja
    ADD CONSTRAINT rwt_prestasi_kerja_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5502 (class 2606 OID 43716)
-- Name: rwt_tugas_belajar rwt_tugas_belajar_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_tugas_belajar
    ADD CONSTRAINT rwt_tugas_belajar_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5628 (class 2606 OID 2502458)
-- Name: rwt_ujikom rwt_ujikom_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.rwt_ujikom
    ADD CONSTRAINT rwt_ujikom_pkey PRIMARY KEY (id);


--
-- TOC entry 5504 (class 2606 OID 43718)
-- Name: schema_version schema_version_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.schema_version
    ADD CONSTRAINT schema_version_pkey PRIMARY KEY (id);


--
-- TOC entry 5506 (class 2606 OID 43720)
-- Name: semen_bpcb_sulsel semen_bpcb_sulsel_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.semen_bpcb_sulsel
    ADD CONSTRAINT semen_bpcb_sulsel_pkey PRIMARY KEY (nip);


--
-- TOC entry 5509 (class 2606 OID 43722)
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- TOC entry 5604 (class 2606 OID 1161015)
-- Name: synch_jumlah_pegawai synch_jumlah_pegawai_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.synch_jumlah_pegawai
    ADD CONSTRAINT synch_jumlah_pegawai_pkey PRIMARY KEY (id);


--
-- TOC entry 5588 (class 2606 OID 347524)
-- Name: tb_nomor_surat tb_nomor_surat_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tb_nomor_surat
    ADD CONSTRAINT tb_nomor_surat_pkey PRIMARY KEY (id);


--
-- TOC entry 5600 (class 2606 OID 1001319)
-- Name: tbl_file_ds_corrector_backup_1 tbl_file_ds_corrector_copy1_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_corrector_backup_1
    ADD CONSTRAINT tbl_file_ds_corrector_copy1_pkey PRIMARY KEY (id);


--
-- TOC entry 5602 (class 2606 OID 1001328)
-- Name: tbl_file_ds_corrector_backup_2 tbl_file_ds_corrector_copy2_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_corrector_backup_2
    ADD CONSTRAINT tbl_file_ds_corrector_copy2_pkey PRIMARY KEY (id);


--
-- TOC entry 5513 (class 2606 OID 43726)
-- Name: tbl_file_ds_corrector tbl_file_ds_corrector_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_corrector
    ADD CONSTRAINT tbl_file_ds_corrector_pkey PRIMARY KEY (id);


--
-- TOC entry 5620 (class 2606 OID 2177116)
-- Name: tbl_file_ds_khusus_login tbl_file_ds_khusus_login_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds_khusus_login
    ADD CONSTRAINT tbl_file_ds_khusus_login_pkey PRIMARY KEY ("ID_FILE");


--
-- TOC entry 5511 (class 2606 OID 43728)
-- Name: tbl_file_ds tbl_file_ds_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ds
    ADD CONSTRAINT tbl_file_ds_pkey PRIMARY KEY (id_file);


--
-- TOC entry 5515 (class 2606 OID 43730)
-- Name: tbl_file_ttd tbl_file_ttd_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_file_ttd
    ADD CONSTRAINT tbl_file_ttd_pkey PRIMARY KEY (id_pns_bkn);


--
-- TOC entry 5590 (class 2606 OID 443115)
-- Name: tbl_kategori_dokumen_penandatangan tbl_kategori_dokumen_penandatangan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_kategori_dokumen_penandatangan
    ADD CONSTRAINT tbl_kategori_dokumen_penandatangan_pkey PRIMARY KEY ("ID_URUT");


--
-- TOC entry 5552 (class 2606 OID 52226)
-- Name: tbl_kategori_dokumen tbl_kategori_dokumen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_kategori_dokumen
    ADD CONSTRAINT tbl_kategori_dokumen_pkey PRIMARY KEY (id_kategori);


--
-- TOC entry 5586 (class 2606 OID 310375)
-- Name: tbl_pengantar_dokumen tbl_pengantar_dokumen_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tbl_pengantar_dokumen
    ADD CONSTRAINT tbl_pengantar_dokumen_pkey PRIMARY KEY (id_pengantar);


--
-- TOC entry 5382 (class 2606 OID 43732)
-- Name: tkpendidikan tkpendidikan_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tkpendidikan
    ADD CONSTRAINT tkpendidikan_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5566 (class 2606 OID 249404)
-- Name: tte_master_variable tte_ master_variable_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_variable
    ADD CONSTRAINT "tte_ master_variable_pkey" PRIMARY KEY (id);


--
-- TOC entry 5560 (class 2606 OID 249383)
-- Name: tte_master_korektor tte_master_korektor_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_korektor
    ADD CONSTRAINT tte_master_korektor_pkey PRIMARY KEY (id);


--
-- TOC entry 5564 (class 2606 OID 249398)
-- Name: tte_master_proses_variable tte_master_proses_variable_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_master_proses_variable
    ADD CONSTRAINT tte_master_proses_variable_pkey PRIMARY KEY (id);


--
-- TOC entry 5570 (class 2606 OID 249419)
-- Name: tte_trx_draft_sk_detil tte_trx_draft_sk_detil_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_draft_sk_detil
    ADD CONSTRAINT tte_trx_draft_sk_detil_pkey PRIMARY KEY (id);


--
-- TOC entry 5568 (class 2606 OID 1107894)
-- Name: tte_trx_draft_sk tte_trx_draft_sk_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_draft_sk
    ADD CONSTRAINT tte_trx_draft_sk_pkey PRIMARY KEY (id);


--
-- TOC entry 5572 (class 2606 OID 249425)
-- Name: tte_trx_korektor_draft tte_trx_korektor_draft_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.tte_trx_korektor_draft
    ADD CONSTRAINT tte_trx_korektor_draft_pkey PRIMARY KEY (id);


--
-- TOC entry 5584 (class 2606 OID 302134)
-- Name: unitkerja_copy1 unitkerja_copy1_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.unitkerja_copy1
    ADD CONSTRAINT unitkerja_copy1_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5395 (class 2606 OID 43734)
-- Name: unitkerja_1234 unitkerja_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.unitkerja_1234
    ADD CONSTRAINT unitkerja_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5384 (class 2606 OID 43736)
-- Name: unitkerja unitkerja_pkey1; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.unitkerja
    ADD CONSTRAINT unitkerja_pkey1 PRIMARY KEY ("ID");


--
-- TOC entry 5517 (class 2606 OID 43738)
-- Name: update_mandiri update_mandiri_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.update_mandiri
    ADD CONSTRAINT update_mandiri_pkey PRIMARY KEY ("ID");


--
-- TOC entry 5519 (class 2606 OID 43740)
-- Name: user_cookies user_cookies_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.user_cookies
    ADD CONSTRAINT user_cookies_pkey PRIMARY KEY (id);


--
-- TOC entry 5521 (class 2606 OID 43742)
-- Name: user_meta user_meta_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.user_meta
    ADD CONSTRAINT user_meta_pkey PRIMARY KEY (meta_id);


--
-- TOC entry 5524 (class 2606 OID 43744)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 5530 (class 2606 OID 43746)
-- Name: wage_2018 wage_copy1_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.wage_2018
    ADD CONSTRAINT wage_copy1_pkey PRIMARY KEY ("GOLONGAN", "WORKING_PERIOD");


--
-- TOC entry 5528 (class 2606 OID 43748)
-- Name: wage wage_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.wage
    ADD CONSTRAINT wage_pkey PRIMARY KEY ("GOLONGAN", "WORKING_PERIOD");


--
-- TOC entry 5606 (class 2606 OID 1636270)
-- Name: z_temp_data_anomali_2023 z_temp_data_anomali_2023_pkey; Type: CONSTRAINT; Schema: hris; Owner: postgres
--

ALTER TABLE ONLY hris.z_temp_data_anomali_2023
    ADD CONSTRAINT z_temp_data_anomali_2023_pkey PRIMARY KEY (id);


--
-- TOC entry 5532 (class 2606 OID 43764)
-- Name: api_controllers api_controllers_pkey; Type: CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_controllers
    ADD CONSTRAINT api_controllers_pkey PRIMARY KEY (id);


--
-- TOC entry 5534 (class 2606 OID 43766)
-- Name: api_kategori api_kategori_pkey; Type: CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_kategori
    ADD CONSTRAINT api_kategori_pkey PRIMARY KEY (id);


--
-- TOC entry 5536 (class 2606 OID 43768)
-- Name: api_keys api_keys_pkey; Type: CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_keys
    ADD CONSTRAINT api_keys_pkey PRIMARY KEY (id);


--
-- TOC entry 5538 (class 2606 OID 43770)
-- Name: api_limits api_limits_pkey; Type: CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_limits
    ADD CONSTRAINT api_limits_pkey PRIMARY KEY (id);


--
-- TOC entry 5540 (class 2606 OID 43772)
-- Name: api_logs api_logs_pkey; Type: CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_logs
    ADD CONSTRAINT api_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 5385 (class 1259 OID 43773)
-- Name: pegawai_GOL_ID; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "pegawai_GOL_ID" ON hris.pegawai USING btree ("GOL_ID");


--
-- TOC entry 5386 (class 1259 OID 43774)
-- Name: pegawai_NIP_BARU; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE UNIQUE INDEX "pegawai_NIP_BARU" ON hris.pegawai USING btree ("NIP_BARU");


--
-- TOC entry 5387 (class 1259 OID 1581500)
-- Name: pegawai_PNS_ID_idx; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE UNIQUE INDEX "pegawai_PNS_ID_idx" ON hris.pegawai USING btree ("PNS_ID");


--
-- TOC entry 5462 (class 1259 OID 43776)
-- Name: pegawai_PNS_ID_idx_copy; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE UNIQUE INDEX "pegawai_PNS_ID_idx_copy" ON hris.pegawai_copy USING btree ("PNS_ID");


--
-- TOC entry 5390 (class 1259 OID 1777574)
-- Name: pegawai_unor_id; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX pegawai_unor_id ON hris.pegawai USING btree ("UNOR_ID");


--
-- TOC entry 5489 (class 1259 OID 43778)
-- Name: rwt_assesmen_nip; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX rwt_assesmen_nip ON hris.rwt_assesmen USING btree ("PNS_NIP");


--
-- TOC entry 5492 (class 1259 OID 1861350)
-- Name: rwt_assesmen_pns_id; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX rwt_assesmen_pns_id ON hris.rwt_assesmen USING btree ("PNS_ID");


--
-- TOC entry 5391 (class 1259 OID 43780)
-- Name: rwt_diklat_fungsional_NIP; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_diklat_fungsional_NIP" ON hris.rwt_diklat_fungsional USING btree ("NIP_BARU");


--
-- TOC entry 5396 (class 1259 OID 43781)
-- Name: rwt_diklat_struktural_NIP; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_diklat_struktural_NIP" ON hris.rwt_diklat_struktural USING btree ("PNS_NIP");


--
-- TOC entry 5399 (class 1259 OID 43782)
-- Name: rwt_diklat_struktural_pns_id; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX rwt_diklat_struktural_pns_id ON hris.rwt_diklat_struktural USING btree ("PNS_ID");


--
-- TOC entry 5495 (class 1259 OID 43783)
-- Name: rwt_penghargaan_ID_GOLONGAN; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_penghargaan_ID_GOLONGAN" ON hris.rwt_penghargaan USING btree ("ID_GOLONGAN");


--
-- TOC entry 5496 (class 1259 OID 43784)
-- Name: rwt_penghargaan_ID_PENGHARGAAN; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_penghargaan_ID_PENGHARGAAN" ON hris.rwt_penghargaan USING btree ("ID_JENIS_PENGHARGAAN");


--
-- TOC entry 5497 (class 1259 OID 43785)
-- Name: rwt_penghargaan_PNS_ID; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_penghargaan_PNS_ID" ON hris.rwt_penghargaan USING btree ("PNS_ID");


--
-- TOC entry 5498 (class 1259 OID 43786)
-- Name: rwt_penghargaan_PNS_NIP; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX "rwt_penghargaan_PNS_NIP" ON hris.rwt_penghargaan USING btree ("PNS_NIP");


--
-- TOC entry 5507 (class 1259 OID 43787)
-- Name: settings_name_idx; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE UNIQUE INDEX settings_name_idx ON hris.settings USING btree (name);


--
-- TOC entry 5522 (class 1259 OID 1244197)
-- Name: username_index; Type: INDEX; Schema: hris; Owner: postgres
--

CREATE INDEX username_index ON hris.users USING btree (username);


--
-- TOC entry 5633 (class 2606 OID 43788)
-- Name: api_access api_access_app_id_fkey; Type: FK CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_access
    ADD CONSTRAINT api_access_app_id_fkey FOREIGN KEY (app_id) REFERENCES webservice.api_keys(id);


--
-- TOC entry 5634 (class 2606 OID 43793)
-- Name: api_access api_access_controller_id_fkey; Type: FK CONSTRAINT; Schema: webservice; Owner: postgres
--

ALTER TABLE ONLY webservice.api_access
    ADD CONSTRAINT api_access_controller_id_fkey FOREIGN KEY (controller_id) REFERENCES webservice.api_controllers(id);


--
-- TOC entry 6086 (class 0 OID 0)
-- Dependencies: 16
-- Name: SCHEMA hris; Type: ACL; Schema: -; Owner: postgres
--

GRANT USAGE ON SCHEMA hris TO ropeg_view;
GRANT USAGE ON SCHEMA hris TO webapi;


--
-- TOC entry 6087 (class 0 OID 0)
-- Dependencies: 249
-- Name: TABLE agama; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.agama TO ropeg_view;
GRANT SELECT ON TABLE hris.agama TO webapi;


--
-- TOC entry 6088 (class 0 OID 0)
-- Dependencies: 251
-- Name: TABLE golongan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.golongan TO ropeg_view;
GRANT SELECT ON TABLE hris.golongan TO webapi;


--
-- TOC entry 6089 (class 0 OID 0)
-- Dependencies: 253
-- Name: TABLE jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jabatan TO ropeg_view;
GRANT SELECT ON TABLE hris.jabatan TO webapi;


--
-- TOC entry 6090 (class 0 OID 0)
-- Dependencies: 256
-- Name: TABLE jenis_diklat_fungsional; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_diklat_fungsional TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_diklat_fungsional TO webapi;


--
-- TOC entry 6091 (class 0 OID 0)
-- Dependencies: 258
-- Name: TABLE jenis_diklat_struktural; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_diklat_struktural TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_diklat_struktural TO webapi;


--
-- TOC entry 6092 (class 0 OID 0)
-- Dependencies: 260
-- Name: TABLE jenis_hukuman; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_hukuman TO webapi;


--
-- TOC entry 6093 (class 0 OID 0)
-- Dependencies: 263
-- Name: TABLE jenis_jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_jabatan TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_jabatan TO webapi;


--
-- TOC entry 6094 (class 0 OID 0)
-- Dependencies: 265
-- Name: TABLE jenis_kawin; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_kawin TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_kawin TO webapi;


--
-- TOC entry 6095 (class 0 OID 0)
-- Dependencies: 267
-- Name: TABLE jenis_pegawai; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_pegawai TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_pegawai TO webapi;


--
-- TOC entry 6096 (class 0 OID 0)
-- Dependencies: 269
-- Name: TABLE jenis_penghargaan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_penghargaan TO webapi;


--
-- TOC entry 6097 (class 0 OID 0)
-- Dependencies: 271
-- Name: TABLE kedudukan_hukum; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kedudukan_hukum TO ropeg_view;
GRANT SELECT ON TABLE hris.kedudukan_hukum TO webapi;


--
-- TOC entry 6098 (class 0 OID 0)
-- Dependencies: 273
-- Name: TABLE lokasi; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.lokasi TO ropeg_view;
GRANT SELECT ON TABLE hris.lokasi TO webapi;


--
-- TOC entry 6099 (class 0 OID 0)
-- Dependencies: 275
-- Name: TABLE pendidikan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pendidikan TO ropeg_view;
GRANT SELECT ON TABLE hris.pendidikan TO webapi;


--
-- TOC entry 6100 (class 0 OID 0)
-- Dependencies: 277
-- Name: TABLE tkpendidikan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tkpendidikan TO ropeg_view;
GRANT SELECT ON TABLE hris.tkpendidikan TO webapi;


--
-- TOC entry 6101 (class 0 OID 0)
-- Dependencies: 279
-- Name: TABLE unitkerja; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja TO ropeg_view WITH GRANT OPTION;
GRANT SELECT ON TABLE hris.unitkerja TO webapi;


--
-- TOC entry 6103 (class 0 OID 0)
-- Dependencies: 281
-- Name: TABLE pegawai; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pegawai TO ropeg_view;
GRANT SELECT ON TABLE hris.pegawai TO webapi;


--
-- TOC entry 6104 (class 0 OID 0)
-- Dependencies: 282
-- Name: TABLE rwt_diklat_fungsional; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_diklat_fungsional TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_diklat_fungsional TO webapi;


--
-- TOC entry 6105 (class 0 OID 0)
-- Dependencies: 283
-- Name: TABLE unitkerja_1234; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_1234 TO ropeg_view;
GRANT SELECT ON TABLE hris.unitkerja_1234 TO webapi;


--
-- TOC entry 6106 (class 0 OID 0)
-- Dependencies: 284
-- Name: TABLE rwt_diklat_struktural; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_diklat_struktural TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_diklat_struktural TO webapi;


--
-- TOC entry 6107 (class 0 OID 0)
-- Dependencies: 285
-- Name: TABLE rwt_golongan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_golongan TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_golongan TO webapi;


--
-- TOC entry 6108 (class 0 OID 0)
-- Dependencies: 288
-- Name: TABLE rwt_pekerjaan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_pekerjaan TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_pekerjaan TO webapi;


--
-- TOC entry 6109 (class 0 OID 0)
-- Dependencies: 289
-- Name: TABLE rwt_pendidikan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_pendidikan TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_pendidikan TO webapi;


--
-- TOC entry 6110 (class 0 OID 0)
-- Dependencies: 291
-- Name: TABLE rwt_prestasi_kerja; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_prestasi_kerja TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_prestasi_kerja TO webapi;


--
-- TOC entry 6121 (class 0 OID 0)
-- Dependencies: 487
-- Name: TABLE tbl_file_ds; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_file_ds TO hcdp_user;
GRANT SELECT,REFERENCES,DELETE,TRIGGER,TRUNCATE,UPDATE ON TABLE hris.tbl_file_ds TO webapi;
GRANT INSERT ON TABLE hris.tbl_file_ds TO webapi WITH GRANT OPTION;


--
-- TOC entry 6123 (class 0 OID 0)
-- Dependencies: 509
-- Name: TABLE vw_unor_satker; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_unor_satker TO webapi;


--
-- TOC entry 6124 (class 0 OID 0)
-- Dependencies: 382
-- Name: TABLE rwt_nine_box; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_nine_box TO webapi;


--
-- TOC entry 6128 (class 0 OID 0)
-- Dependencies: 650
-- Name: TABLE absen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.absen TO webapi;


--
-- TOC entry 6130 (class 0 OID 0)
-- Dependencies: 385
-- Name: TABLE activities; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.activities TO ropeg_view;
GRANT SELECT ON TABLE hris.activities TO webapi;


--
-- TOC entry 6131 (class 0 OID 0)
-- Dependencies: 386
-- Name: TABLE anak; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.anak TO webapi;


--
-- TOC entry 6135 (class 0 OID 0)
-- Dependencies: 623
-- Name: TABLE arsip; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.arsip TO webapi;


--
-- TOC entry 6140 (class 0 OID 0)
-- Dependencies: 388
-- Name: TABLE backup_jabatan_23_mar_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.backup_jabatan_23_mar_2020 TO webapi;


--
-- TOC entry 6141 (class 0 OID 0)
-- Dependencies: 661
-- Name: TABLE backup_unor_induk_13_nov_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.backup_unor_induk_13_nov_2020 TO webapi;


--
-- TOC entry 6142 (class 0 OID 0)
-- Dependencies: 627
-- Name: TABLE backup_unor_jabatan_01_09_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.backup_unor_jabatan_01_09_2020 TO webapi;


--
-- TOC entry 6143 (class 0 OID 0)
-- Dependencies: 389
-- Name: TABLE baperjakat; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.baperjakat TO webapi;


--
-- TOC entry 6145 (class 0 OID 0)
-- Dependencies: 391
-- Name: TABLE ci3_sessions; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.ci3_sessions TO ropeg_view;
GRANT SELECT ON TABLE hris.ci3_sessions TO webapi;


--
-- TOC entry 6146 (class 0 OID 0)
-- Dependencies: 392
-- Name: TABLE daftar_rohaniawan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.daftar_rohaniawan TO webapi;


--
-- TOC entry 6148 (class 0 OID 0)
-- Dependencies: 394
-- Name: TABLE data_jabatan_tomi_04_04_2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.data_jabatan_tomi_04_04_2019 TO webapi;


--
-- TOC entry 6149 (class 0 OID 0)
-- Dependencies: 395
-- Name: TABLE data_jabatan_tomi_11_06_2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.data_jabatan_tomi_11_06_2019 TO webapi;


--
-- TOC entry 6150 (class 0 OID 0)
-- Dependencies: 396
-- Name: TABLE email_queue; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.email_queue TO webapi;


--
-- TOC entry 6152 (class 0 OID 0)
-- Dependencies: 654
-- Name: TABLE hari_libur; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.hari_libur TO webapi;


--
-- TOC entry 6154 (class 0 OID 0)
-- Dependencies: 398
-- Name: TABLE instansi; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.instansi TO ropeg_view;
GRANT SELECT ON TABLE hris.instansi TO webapi;


--
-- TOC entry 6158 (class 0 OID 0)
-- Dependencies: 399
-- Name: TABLE istri; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.istri TO webapi;


--
-- TOC entry 6165 (class 0 OID 0)
-- Dependencies: 611
-- Name: TABLE izin; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.izin TO webapi;


--
-- TOC entry 6167 (class 0 OID 0)
-- Dependencies: 655
-- Name: TABLE izin_alasan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.izin_alasan TO webapi;


--
-- TOC entry 6170 (class 0 OID 0)
-- Dependencies: 657
-- Name: TABLE izin_verifikasi; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.izin_verifikasi TO webapi;


--
-- TOC entry 6172 (class 0 OID 0)
-- Dependencies: 680
-- Name: TABLE jabatan_19_jan_2021; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jabatan_19_jan_2021 TO webapi;


--
-- TOC entry 6173 (class 0 OID 0)
-- Dependencies: 401
-- Name: TABLE jabatan_backup_11_06_2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jabatan_backup_11_06_2019 TO webapi;


--
-- TOC entry 6175 (class 0 OID 0)
-- Dependencies: 403
-- Name: TABLE jabatan_copy; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jabatan_copy TO webapi;


--
-- TOC entry 6177 (class 0 OID 0)
-- Dependencies: 624
-- Name: TABLE jenis_arsip; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_arsip TO webapi;


--
-- TOC entry 6180 (class 0 OID 0)
-- Dependencies: 612
-- Name: TABLE jenis_izin; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_izin TO webapi;


--
-- TOC entry 6182 (class 0 OID 0)
-- Dependencies: 404
-- Name: TABLE jenis_kp; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.jenis_kp TO ropeg_view;
GRANT SELECT ON TABLE hris.jenis_kp TO webapi;


--
-- TOC entry 6188 (class 0 OID 0)
-- Dependencies: 405
-- Name: TABLE kandidat_baperjakat; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kandidat_baperjakat TO webapi;


--
-- TOC entry 6190 (class 0 OID 0)
-- Dependencies: 407
-- Name: TABLE kategori_ds; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kategori_ds TO webapi;


--
-- TOC entry 6192 (class 0 OID 0)
-- Dependencies: 636
-- Name: TABLE kategori_jenis_arsip; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kategori_jenis_arsip TO webapi;


--
-- TOC entry 6194 (class 0 OID 0)
-- Dependencies: 409
-- Name: TABLE kpkn; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kpkn TO ropeg_view;
GRANT SELECT ON TABLE hris.kpkn TO webapi;


--
-- TOC entry 6195 (class 0 OID 0)
-- Dependencies: 411
-- Name: TABLE kuota_jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kuota_jabatan TO ropeg_view;
GRANT SELECT ON TABLE hris.kuota_jabatan TO webapi;


--
-- TOC entry 6196 (class 0 OID 0)
-- Dependencies: 412
-- Name: TABLE kuota_jabatan_1209; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kuota_jabatan_1209 TO webapi;


--
-- TOC entry 6197 (class 0 OID 0)
-- Dependencies: 413
-- Name: TABLE kuota_jabatan_16sep2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.kuota_jabatan_16sep2019 TO webapi;


--
-- TOC entry 6198 (class 0 OID 0)
-- Dependencies: 414
-- Name: TABLE layanan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.layanan TO webapi;


--
-- TOC entry 6199 (class 0 OID 0)
-- Dependencies: 415
-- Name: TABLE layanan_tipe; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.layanan_tipe TO webapi;


--
-- TOC entry 6202 (class 0 OID 0)
-- Dependencies: 418
-- Name: TABLE layanan_usulan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.layanan_usulan TO webapi;


--
-- TOC entry 6206 (class 0 OID 0)
-- Dependencies: 651
-- Name: TABLE line_approval_izin; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.line_approval_izin TO webapi;


--
-- TOC entry 6210 (class 0 OID 0)
-- Dependencies: 420
-- Name: TABLE log_ds; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.log_ds TO webapi;


--
-- TOC entry 6213 (class 0 OID 0)
-- Dependencies: 422
-- Name: TABLE log_transaksi; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT,REFERENCES ON TABLE hris.log_transaksi TO ropeg_view;
GRANT SELECT ON TABLE hris.log_transaksi TO webapi;


--
-- TOC entry 6215 (class 0 OID 0)
-- Dependencies: 425
-- Name: TABLE login_attempts; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.login_attempts TO ropeg_view;
GRANT SELECT ON TABLE hris.login_attempts TO webapi;


--
-- TOC entry 6216 (class 0 OID 0)
-- Dependencies: 426
-- Name: TABLE mst_templates; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.mst_templates TO webapi;


--
-- TOC entry 6218 (class 0 OID 0)
-- Dependencies: 685
-- Name: TABLE pns_aktif; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pns_aktif TO webapi;


--
-- TOC entry 6219 (class 0 OID 0)
-- Dependencies: 658
-- Name: TABLE mv_kategori_ds; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.mv_kategori_ds TO webapi;


--
-- TOC entry 6220 (class 0 OID 0)
-- Dependencies: 613
-- Name: TABLE pegawai_atasan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pegawai_atasan TO webapi;


--
-- TOC entry 6224 (class 0 OID 0)
-- Dependencies: 468
-- Name: TABLE rwt_assesmen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_assesmen TO webapi;


--
-- TOC entry 6225 (class 0 OID 0)
-- Dependencies: 646
-- Name: TABLE mv_riwayat_asesmen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.mv_riwayat_asesmen TO webapi;


--
-- TOC entry 6226 (class 0 OID 0)
-- Dependencies: 428
-- Name: TABLE nama_unit; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.nama_unit TO webapi;


--
-- TOC entry 6227 (class 0 OID 0)
-- Dependencies: 429
-- Name: TABLE nip_pejabat; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.nip_pejabat TO ropeg_view;
GRANT SELECT ON TABLE hris.nip_pejabat TO webapi;


--
-- TOC entry 6230 (class 0 OID 0)
-- Dependencies: 431
-- Name: TABLE orang_tua; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.orang_tua TO webapi;


--
-- TOC entry 6232 (class 0 OID 0)
-- Dependencies: 433
-- Name: TABLE organisasi_ropeg; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.organisasi_ropeg TO ropeg_view;
GRANT SELECT ON TABLE hris.organisasi_ropeg TO webapi;


--
-- TOC entry 6233 (class 0 OID 0)
-- Dependencies: 644
-- Name: TABLE pegawai_08_sept_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pegawai_08_sept_2020 TO webapi;


--
-- TOC entry 6235 (class 0 OID 0)
-- Dependencies: 435
-- Name: TABLE pegawai_bkn; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pegawai_bkn TO webapi;


--
-- TOC entry 6237 (class 0 OID 0)
-- Dependencies: 436
-- Name: TABLE pegawai_copy; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pegawai_copy TO webapi;


--
-- TOC entry 6238 (class 0 OID 0)
-- Dependencies: 437
-- Name: TABLE pengajuan_tubel; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pengajuan_tubel TO webapi;


--
-- TOC entry 6242 (class 0 OID 0)
-- Dependencies: 439
-- Name: TABLE perkiraan_kpo; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.perkiraan_kpo TO webapi;


--
-- TOC entry 6245 (class 0 OID 0)
-- Dependencies: 443
-- Name: TABLE perkiraan_ppo; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.perkiraan_ppo TO webapi;


--
-- TOC entry 6246 (class 0 OID 0)
-- Dependencies: 444
-- Name: TABLE perkiraan_usulan_log; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.perkiraan_usulan_log TO webapi;


--
-- TOC entry 6248 (class 0 OID 0)
-- Dependencies: 447
-- Name: TABLE permissions; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.permissions TO ropeg_view;
GRANT SELECT ON TABLE hris.permissions TO webapi;


--
-- TOC entry 6250 (class 0 OID 0)
-- Dependencies: 448
-- Name: TABLE pindah_unit; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pindah_unit TO webapi;


--
-- TOC entry 6252 (class 0 OID 0)
-- Dependencies: 450
-- Name: TABLE pns_aktif_old; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.pns_aktif_old TO ropeg_view WITH GRANT OPTION;
GRANT SELECT ON TABLE hris.pns_aktif_old TO webapi;


--
-- TOC entry 6253 (class 0 OID 0)
-- Dependencies: 451
-- Name: TABLE ref_jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.ref_jabatan TO ropeg_view;
GRANT SELECT ON TABLE hris.ref_jabatan TO webapi;


--
-- TOC entry 6254 (class 0 OID 0)
-- Dependencies: 452
-- Name: TABLE ref_tunjangan_jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.ref_tunjangan_jabatan TO webapi;


--
-- TOC entry 6255 (class 0 OID 0)
-- Dependencies: 453
-- Name: TABLE ref_tunjangan_kinerja; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.ref_tunjangan_kinerja TO webapi;


--
-- TOC entry 6257 (class 0 OID 0)
-- Dependencies: 455
-- Name: TABLE rekap_agama_jenis_kelamin; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rekap_agama_jenis_kelamin TO ropeg_view;
GRANT SELECT ON TABLE hris.rekap_agama_jenis_kelamin TO webapi;


--
-- TOC entry 6259 (class 0 OID 0)
-- Dependencies: 456
-- Name: TABLE role_permissions; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.role_permissions TO ropeg_view;
GRANT SELECT ON TABLE hris.role_permissions TO webapi;


--
-- TOC entry 6261 (class 0 OID 0)
-- Dependencies: 459
-- Name: TABLE roles; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.roles TO ropeg_view;
GRANT SELECT ON TABLE hris.roles TO webapi;


--
-- TOC entry 6262 (class 0 OID 0)
-- Dependencies: 460
-- Name: TABLE roles_users; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.roles_users TO webapi;


--
-- TOC entry 6264 (class 0 OID 0)
-- Dependencies: 461
-- Name: TABLE rpt_golongan_bulan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rpt_golongan_bulan TO webapi;


--
-- TOC entry 6266 (class 0 OID 0)
-- Dependencies: 463
-- Name: TABLE rpt_jumlah_asn; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rpt_jumlah_asn TO webapi;


--
-- TOC entry 6269 (class 0 OID 0)
-- Dependencies: 466
-- Name: TABLE rpt_pendidikan_bulan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rpt_pendidikan_bulan TO webapi;


--
-- TOC entry 6272 (class 0 OID 0)
-- Dependencies: 625
-- Name: TABLE rwt_assesmen_bak; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_assesmen_bak TO webapi;


--
-- TOC entry 6273 (class 0 OID 0)
-- Dependencies: 647
-- Name: TABLE rwt_assesmen_id_file_exist_13_09_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_assesmen_id_file_exist_13_09_2020 TO webapi;


--
-- TOC entry 6275 (class 0 OID 0)
-- Dependencies: 470
-- Name: TABLE rwt_hukdis; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_hukdis TO webapi;


--
-- TOC entry 6277 (class 0 OID 0)
-- Dependencies: 286
-- Name: TABLE rwt_jabatan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT,REFERENCES ON TABLE hris.rwt_jabatan TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_jabatan TO webapi;


--
-- TOC entry 6278 (class 0 OID 0)
-- Dependencies: 472
-- Name: TABLE rwt_kgb; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_kgb TO webapi;


--
-- TOC entry 6281 (class 0 OID 0)
-- Dependencies: 474
-- Name: TABLE rwt_kursus; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_kursus TO webapi;


--
-- TOC entry 6283 (class 0 OID 0)
-- Dependencies: 477
-- Name: TABLE rwt_penghargaan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_penghargaan TO webapi;


--
-- TOC entry 6286 (class 0 OID 0)
-- Dependencies: 479
-- Name: TABLE rwt_pindah_unit_kerja; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_pindah_unit_kerja TO ropeg_view;
GRANT SELECT ON TABLE hris.rwt_pindah_unit_kerja TO webapi;


--
-- TOC entry 6287 (class 0 OID 0)
-- Dependencies: 606
-- Name: TABLE rwt_pns_cpns; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_pns_cpns TO webapi;


--
-- TOC entry 6289 (class 0 OID 0)
-- Dependencies: 480
-- Name: TABLE rwt_tugas_belajar; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.rwt_tugas_belajar TO webapi;


--
-- TOC entry 6292 (class 0 OID 0)
-- Dependencies: 482
-- Name: TABLE schema_version; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.schema_version TO ropeg_view;
GRANT SELECT ON TABLE hris.schema_version TO webapi;


--
-- TOC entry 6294 (class 0 OID 0)
-- Dependencies: 484
-- Name: TABLE semen_bpcb_sulsel; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.semen_bpcb_sulsel TO webapi;


--
-- TOC entry 6295 (class 0 OID 0)
-- Dependencies: 485
-- Name: TABLE settings; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.settings TO ropeg_view;
GRANT SELECT ON TABLE hris.settings TO webapi;


--
-- TOC entry 6297 (class 0 OID 0)
-- Dependencies: 614
-- Name: TABLE sisa_cuti; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.sisa_cuti TO webapi;


--
-- TOC entry 6300 (class 0 OID 0)
-- Dependencies: 678
-- Name: TABLE tb_nomor_batasan; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tb_nomor_batasan TO webapi;


--
-- TOC entry 6301 (class 0 OID 0)
-- Dependencies: 679
-- Name: TABLE tb_nomor_surat; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tb_nomor_surat TO webapi;


--
-- TOC entry 6302 (class 0 OID 0)
-- Dependencies: 681
-- Name: TABLE "tbl_NUMPANG1"; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris."tbl_NUMPANG1" TO webapi;


--
-- TOC entry 6303 (class 0 OID 0)
-- Dependencies: 683
-- Name: TABLE "tbl_NUMPANG2"; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris."tbl_NUMPANG2" TO webapi;


--
-- TOC entry 6304 (class 0 OID 0)
-- Dependencies: 618
-- Name: TABLE tbl_cek; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_cek TO webapi;


--
-- TOC entry 6305 (class 0 OID 0)
-- Dependencies: 619
-- Name: TABLE tbl_cek_2; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_cek_2 TO webapi;


--
-- TOC entry 6308 (class 0 OID 0)
-- Dependencies: 488
-- Name: TABLE tbl_file_ds_corrector; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON TABLE hris.tbl_file_ds_corrector TO webapi;


--
-- TOC entry 6310 (class 0 OID 0)
-- Dependencies: 489
-- Name: SEQUENCE tbl_file_ds_corrector_id_seq; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON SEQUENCE hris.tbl_file_ds_corrector_id_seq TO webapi;


--
-- TOC entry 6313 (class 0 OID 0)
-- Dependencies: 721
-- Name: TABLE tbl_file_ds_corrector_backup_1; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON TABLE hris.tbl_file_ds_corrector_backup_1 TO webapi;


--
-- TOC entry 6316 (class 0 OID 0)
-- Dependencies: 722
-- Name: TABLE tbl_file_ds_corrector_backup_2; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON TABLE hris.tbl_file_ds_corrector_backup_2 TO webapi;


--
-- TOC entry 6318 (class 0 OID 0)
-- Dependencies: 490
-- Name: SEQUENCE tbl_file_ds_id_seq; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON SEQUENCE hris.tbl_file_ds_id_seq TO webapi;


--
-- TOC entry 6319 (class 0 OID 0)
-- Dependencies: 616
-- Name: TABLE tbl_file_ds_riwayat; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON TABLE hris.tbl_file_ds_riwayat TO webapi;


--
-- TOC entry 6321 (class 0 OID 0)
-- Dependencies: 617
-- Name: SEQUENCE tbl_file_ds_riwayat_id_riwayat_seq; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON SEQUENCE hris.tbl_file_ds_riwayat_id_riwayat_seq TO webapi;


--
-- TOC entry 6322 (class 0 OID 0)
-- Dependencies: 703
-- Name: TABLE tbl_file_ds_riwayat_backup_1; Type: ACL; Schema: hris; Owner: postgres
--

GRANT ALL ON TABLE hris.tbl_file_ds_riwayat_backup_1 TO webapi;


--
-- TOC entry 6323 (class 0 OID 0)
-- Dependencies: 491
-- Name: TABLE tbl_file_ttd; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_file_ttd TO webapi;


--
-- TOC entry 6324 (class 0 OID 0)
-- Dependencies: 615
-- Name: TABLE tbl_kategori_dokumen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_kategori_dokumen TO webapi;


--
-- TOC entry 6325 (class 0 OID 0)
-- Dependencies: 662
-- Name: TABLE tbl_pengantar_dokumen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tbl_pengantar_dokumen TO webapi;


--
-- TOC entry 6326 (class 0 OID 0)
-- Dependencies: 640
-- Name: TABLE tte_master_variable; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_master_variable TO webapi;


--
-- TOC entry 6328 (class 0 OID 0)
-- Dependencies: 637
-- Name: TABLE tte_master_korektor; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_master_korektor TO webapi;


--
-- TOC entry 6330 (class 0 OID 0)
-- Dependencies: 638
-- Name: TABLE tte_master_proses; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_master_proses TO webapi;


--
-- TOC entry 6332 (class 0 OID 0)
-- Dependencies: 639
-- Name: TABLE tte_master_proses_variable; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_master_proses_variable TO webapi;


--
-- TOC entry 6335 (class 0 OID 0)
-- Dependencies: 641
-- Name: TABLE tte_trx_draft_sk; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_trx_draft_sk TO webapi;


--
-- TOC entry 6336 (class 0 OID 0)
-- Dependencies: 642
-- Name: TABLE tte_trx_draft_sk_detil; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_trx_draft_sk_detil TO webapi;


--
-- TOC entry 6339 (class 0 OID 0)
-- Dependencies: 643
-- Name: TABLE tte_trx_korektor_draft; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.tte_trx_korektor_draft TO webapi;


--
-- TOC entry 6341 (class 0 OID 0)
-- Dependencies: 626
-- Name: TABLE unitkerja_01_sept_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_01_sept_2020 TO webapi;


--
-- TOC entry 6342 (class 0 OID 0)
-- Dependencies: 492
-- Name: TABLE unitkerja_04_04_2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_04_04_2019 TO webapi;


--
-- TOC entry 6343 (class 0 OID 0)
-- Dependencies: 493
-- Name: TABLE unitkerja_10_feb_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_10_feb_2020 TO webapi;


--
-- TOC entry 6344 (class 0 OID 0)
-- Dependencies: 494
-- Name: TABLE unitkerja_11_04_2019; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_11_04_2019 TO webapi;


--
-- TOC entry 6345 (class 0 OID 0)
-- Dependencies: 620
-- Name: TABLE unitkerja_27_juli_2020; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_27_juli_2020 TO webapi;


--
-- TOC entry 6346 (class 0 OID 0)
-- Dependencies: 495
-- Name: TABLE unitkerja_bak; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_bak TO webapi;


--
-- TOC entry 6347 (class 0 OID 0)
-- Dependencies: 660
-- Name: TABLE unitkerja_copy1; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.unitkerja_copy1 TO webapi;


--
-- TOC entry 6348 (class 0 OID 0)
-- Dependencies: 497
-- Name: TABLE update_mandiri; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.update_mandiri TO webapi;


--
-- TOC entry 6350 (class 0 OID 0)
-- Dependencies: 499
-- Name: TABLE user_cookies; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.user_cookies TO ropeg_view;
GRANT SELECT ON TABLE hris.user_cookies TO webapi;


--
-- TOC entry 6352 (class 0 OID 0)
-- Dependencies: 503
-- Name: TABLE user_meta; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.user_meta TO ropeg_view;
GRANT SELECT ON TABLE hris.user_meta TO webapi;


--
-- TOC entry 6353 (class 0 OID 0)
-- Dependencies: 504
-- Name: TABLE users; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.users TO ropeg_view;
GRANT SELECT ON TABLE hris.users TO webapi;


--
-- TOC entry 6354 (class 0 OID 0)
-- Dependencies: 505
-- Name: TABLE usulan_dokumen; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.usulan_dokumen TO webapi;


--
-- TOC entry 6356 (class 0 OID 0)
-- Dependencies: 659
-- Name: TABLE v_kategori_ds; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.v_kategori_ds TO webapi;


--
-- TOC entry 6358 (class 0 OID 0)
-- Dependencies: 507
-- Name: TABLE vw_list_eselon2; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_list_eselon2 TO webapi;


--
-- TOC entry 6359 (class 0 OID 0)
-- Dependencies: 508
-- Name: TABLE vw_unit_list_asli; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_unit_list_asli TO webapi;


--
-- TOC entry 6361 (class 0 OID 0)
-- Dependencies: 510
-- Name: TABLE vw_unor_satker_copy1; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_unor_satker_copy1 TO webapi;


--
-- TOC entry 6362 (class 0 OID 0)
-- Dependencies: 511
-- Name: TABLE vw_unor_satker_w_eselonid; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_unor_satker_w_eselonid TO webapi;


--
-- TOC entry 6363 (class 0 OID 0)
-- Dependencies: 858
-- Name: TABLE vw_unor_satker_w_id_eselon1; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.vw_unor_satker_w_id_eselon1 TO webapi;


--
-- TOC entry 6364 (class 0 OID 0)
-- Dependencies: 512
-- Name: TABLE wage; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.wage TO webapi;


--
-- TOC entry 6365 (class 0 OID 0)
-- Dependencies: 513
-- Name: TABLE wage_2018; Type: ACL; Schema: hris; Owner: postgres
--

GRANT SELECT ON TABLE hris.wage_2018 TO webapi;


-- Completed on 2025-03-10 14:53:50 WIB

--
-- PostgreSQL database dump complete
--

