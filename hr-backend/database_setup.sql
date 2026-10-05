CREATE TABLE acant_positions (
  id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  prefix varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  district varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  sub_district varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_line varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  staff_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  acant_date varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  doc_no varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  doc_date varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  doc_file varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE moph_staff (
  id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  gency_prefix varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gency_district varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gency_sub_district varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gency_group varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gency_work varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gency_match varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_level varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_line varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  position_status varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  staff_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  personal_prefix varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  personal_fname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  personal_lname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  personal_id_card varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  hire_date varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  hire_qual varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  grad_date varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  gpa varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  license_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  license_no varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  license_issue varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  license_expire varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
