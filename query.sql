desc formcegahs;

laporanbawaslu@gmail.com
bawaslu2023

;
select * from users; where email='pencegahan@bawaslu.go.id';

select id,no_form from formcegahs limit 50; -- 6.185 detik
select * from formcegahs_clone where no_form = '030/F.CEGAH/PM.NT.11.531115//2024';

 -- create index idx_no_form on formcegahs_clone(no_form);
SELECT
	`bentuks`.`bentuk`,
	`formcegahs`.`bentuk` AS `id_bentuk`
FROM
	`formcegahs`
	LEFT JOIN `bentuks` ON `formcegahs`.`bentuk` = `bentuks`.`id`
GROUP BY
	`bentuks`.`bentuk`,`formcegahs`.`bentuk`
ORDER BY
	`bentuks`.`bentuk` ASC;

select * from formcegahs where wp_id="1" limit 1;
create index idx_formcegahs_no_form on formcegahs(no_form);
create index idx_petugas_kdpetugas on petugas(kd_petugas);
create index idx_bentuks_bentuk on bentuks(bentuk);
create index idx_formcegahs_bentuk on formcegahs(bentuk);
create index idx_jenis_jenis on jenis(jenis);
create index idx_formcegahs_jenis on formcegahs(jenis);

SELECT
	`bentuks`.`bentuk`,
	`formcegahs`.`bentuk` AS `id_bentuk`
FROM
	`formcegahs`
	JOIN `bentuks` ON `formcegahs`.`bentuk` = `bentuks`.`id`

WHERE 
	
;	
select id_provinsi,bentuk,id_divisi,jenis,id_provinsi,id_kabupaten,id_kecamatan,id_kelurahan,created_at 
from formcegahs 
where 
bentuk = '0' and 
(STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d')) between '2024-01-01' and '2024-05-31' 
and id_divisi='PM' 
and jenis='127' 
-- and id_provinsi='33'
-- and id_kabupaten='3301'
order by id_divisi,jenis,created_at desc;
;

select id_provinsi,bentuk,id_divisi,jenis,id_provinsi,id_kabupaten,id_kecamatan,id_kelurahan,created_at 
from formcegahs order by created_at desc limit 10;


select count(*) as aggregate from `formcegahs` where `bentuk` = 0 and (STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d')) between '2024-01-01' and '2024-01-31' and `formcegahs`.`id_provinsi` <> "" and `bentuk` = 0;


select count(*) as aggregate from `formcegahs` where `bentuk` = '0' and (STR_TO_DATE(formcegahs.created_at,'%Y-%m-%d')) between 2024-01-01 and 2024-05-31 and `formcegahs`.`id_provinsi` <> "" and `bentuk` = '0';

select * from twp;

CREATE TABLE `tbl_kp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kp` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) 
;

CREATE TABLE `tbl_wp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_wp` varchar(200) DEFAULT NULL,
  `kp_id` int DEFAULT NULL,
  `kdpro` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
); 


alter table `tbl_kp` add COLUMN `created_at` timestamp NULL DEFAULT NULL;
alter table `tbl_kp` add COLUMN `updated_at` timestamp NULL DEFAULT NULL;

alter table `tbl_wp` add COLUMN `created_at` timestamp NULL DEFAULT NULL;
alter table `tbl_wp` add COLUMN `updated_at` timestamp NULL DEFAULT NULL;

alter table `formcegahs` add COLUMN `wp_id` int NULL;
update formcegahs set wp_id=1 where wp_id is null ;
select * from formcegahs limit 1;

CREATE TABLE `tkp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kp` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) 
;

CREATE TABLE `twp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_wp` varchar(200) DEFAULT NULL,
  `kp_id` int DEFAULT NULL,
  `kdpro` char(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
); 

alter table `twp` add COLUMN `kabkot` char(4) DEFAULT NULL AFTER `kdpro`;
select * from twp;

INSERT INTO `tkp` (`id`, `nama_kp`) VALUES
(1, 'Nasional'),
(2, 'Propinsi'),
(3, 'Kabupaten'),
(4, 'Kota');

INSERT INTO `twp` (`id`, `nama_wp`, `kp_id`, `kdpro`) VALUES
(1, 'Pemilu 2024', 1, '00'),
(2, 'Pilkada DKI 2024', 2, '31'),
(3, 'Pilkada JABAR 2024', 2, '32');

INSERT INTO `twp` (`id`, `nama_wp`, `kp_id`, `kdpro`) VALUES
(1, 'Pemilu 2024', 1, '00'),
(2, 'Pilkada DKI 2024', 2, '31'),
(3, 'Pilkada JABAR 2024', 2, '32');


select * from users where id=911;
select * from users where kabkota like '12%'; limit 1;
select * from users where Provinsi=12;
select * from users where id=5179;
select * from twp where kabkot='1200' or kabkot='1272';

-- $2y$10$qsNwKmH5mnAYZUkLcHpGHuhU46ZITZKKHtRF56oYsYgncIDpt06YG
-- ganti -- $2y$10$qy.GTeGTuckbHQKtCNnR4OW0SB2pvcreZSath.WoQqWqGhojkNSdC
-- bawaslu2023
-- panwascamsiantarsitalasari@gmail.com
