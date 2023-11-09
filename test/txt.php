<?
echo"
  REPLACE INTO p_staff (s_id, s_name ,s_email , s_tel,s_status) VALUES
('S20044','ภราดร คงใจ','naratsaphong@hotmail.com ','089-7471269','1'),
('S20045','ปัณฑ์ชนก แก้วโชติ','panchanok.k39@gmail.com','086-9604794','1'),
('S20046','โกศล พงษ์ภัทรวิทย์','pkosol@gmail.com','','1'),
('S20047','ศุภมงคล พุทธวงค์','supphamongkon.p@gmail.com','062-524-2894','1'),
('S20048','ณัฐนันท์ พิพิธสุนทรศานต์','nuttananaonzbb@gmail.com','','1'),
('S20049','อภิสรา แสนสุวรรณา','Pangpondapis@gmail.com','','1');

REPLACE INTO p_staff_clinic (s_id, sc_id ,sc_pwd , clinic_id, job_id, sc_status) VALUES
('S20044','R2027','VtZJWd','RBK','LB','1'),
('S20045','R2028','UeZBW6','RBK','LB','1'),
('S20046','R2029','xEqJWj','RBK','CSL','1'),
('S20047','R2030','JHBbgz','RBK','LB','1'),
('S20048','R2031','5YZwUX','RHY','LB','1'),
('S20049','R2032','CAJx9V','RHY','LB','1');

REPLACE INTO login_user_level (login_id, auth_level ,pass_key , full_name,email,contact,clinic_id, sex, create_by, create_on, blocked ) VALUES
('R2027','2','VtZJWd','ภราดร คงใจ','naratsaphong@hotmail.com ','089-7471269','CZ69T7Q','Male','super_admin','2020-03-27','0'),
('R2028','2','UeZBW6','ปัณฑ์ชนก แก้วโชติ','panchanok.k39@gmail.com','086-9604794','CZ69T7Q','Male','super_admin','2020-03-27','0'),
('R2029','9','xEqJWj','โกศล พงษ์ภัทรวิทย์','pkosol@gmail.com','','CZ69T7Q','Male','super_admin','2020-03-27','0'),
('R2030','2','JHBbgz','ศุภมงคล พุทธวงค์','supphamongkon.p@gmail.com','062-524-2894','CZ69T7Q','Male','super_admin','2020-03-27','0'),
('R2031','2','5YZwUX','ณัฐนันท์ พิพิธสุนทรศานต์','nuttananaonzbb@gmail.com','','2ZI59DS','Male','super_admin','2020-03-27','0'),
('R2032','2','CAJx9V','อภิสรา แสนสุวรรณา','Pangpondapis@gmail.com','','2ZI59DS','Male','super_admin','2020-03-27','0');




";

?>
