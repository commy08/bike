-- ----------------------------
-- Table structure for `provinces`
-- ----------------------------
CREATE TABLE `provinces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_code` int(11) NOT NULL,
  `province_name` varchar(150) NOT NULL,
  `province_name_eng` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_provinces_code` (`province_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of provinces
-- ----------------------------
INSERT INTO `provinces` VALUES ('1', '10', 'กรุงเทพมหานคร', 'Bangkok');
INSERT INTO `provinces` VALUES ('2', '11', 'สมุทรปราการ', 'Samut Prakarn');
INSERT INTO `provinces` VALUES ('3', '12', 'นนทบุรี', 'Nonthaburi');
INSERT INTO `provinces` VALUES ('4', '13', 'ปทุมธานี', 'Pathum Thani');
INSERT INTO `provinces` VALUES ('5', '14', 'พระนครศรีอยุธยา', 'Phra Nakhon Si Ayutthaya');
INSERT INTO `provinces` VALUES ('6', '15', 'อ่างทอง', 'Ang Thong');
INSERT INTO `provinces` VALUES ('7', '16', 'ลพบุรี', 'Lop Buri');
INSERT INTO `provinces` VALUES ('8', '17', 'สิงห์บุรี', 'Sing Buri');
INSERT INTO `provinces` VALUES ('9', '18', 'ชัยนาท', 'Chai Nat');
INSERT INTO `provinces` VALUES ('10', '19', 'สระบุรี', 'Saraburi');
INSERT INTO `provinces` VALUES ('11', '20', 'ชลบุรี', 'Chon Buri');
INSERT INTO `provinces` VALUES ('12', '21', 'ระยอง', 'Rayong');
INSERT INTO `provinces` VALUES ('13', '22', 'จันทบุรี', 'Chanthaburi');
INSERT INTO `provinces` VALUES ('14', '23', 'ตราด', 'Trat');
INSERT INTO `provinces` VALUES ('15', '24', 'ฉะเชิงเทรา', 'Chachoengsao');
INSERT INTO `provinces` VALUES ('16', '25', 'ปราจีนบุรี', 'Prachin Buri');
INSERT INTO `provinces` VALUES ('17', '26', 'นครนายก', 'Nakhon Nayok');
INSERT INTO `provinces` VALUES ('18', '27', 'สระแก้ว', 'Sa kaeo');
INSERT INTO `provinces` VALUES ('19', '30', 'นครราชสีมา', 'Nakhon Ratchasima');
INSERT INTO `provinces` VALUES ('20', '31', 'บุรีรัมย์', 'Buri Ram');
INSERT INTO `provinces` VALUES ('21', '32', 'สุรินทร์', 'Surin');
INSERT INTO `provinces` VALUES ('22', '33', 'ศรีสะเกษ', 'Si Sa Ket');
INSERT INTO `provinces` VALUES ('23', '34', 'อุบลราชธานี', 'Ubon Ratchathani');
INSERT INTO `provinces` VALUES ('24', '35', 'ยโสธร', 'Yasothon');
INSERT INTO `provinces` VALUES ('25', '36', 'ชัยภูมิ', 'Chaiyaphum');
INSERT INTO `provinces` VALUES ('26', '37', 'อำนาจเจริญ', 'Amnat Charoen');
INSERT INTO `provinces` VALUES ('27', '38', 'บึงกาฬ', 'Bueng Kan');
INSERT INTO `provinces` VALUES ('28', '39', 'หนองบัวลำภู', 'Nong Bua Lam Phu');
INSERT INTO `provinces` VALUES ('29', '40', 'ขอนแก่น', 'Khon Kaen');
INSERT INTO `provinces` VALUES ('30', '41', 'อุดรธานี', 'Udon Thani');
INSERT INTO `provinces` VALUES ('31', '42', 'เลย', 'Loei');
INSERT INTO `provinces` VALUES ('32', '43', 'หนองคาย', 'Nong Khai');
INSERT INTO `provinces` VALUES ('33', '44', 'มหาสารคาม', 'Maha Sarakham');
INSERT INTO `provinces` VALUES ('34', '45', 'ร้อยเอ็ด', 'Roi Et');
INSERT INTO `provinces` VALUES ('35', '46', 'กาฬสินธุ์', 'Kalasin');
INSERT INTO `provinces` VALUES ('36', '47', 'สกลนคร', 'Sakon Nakhon');
INSERT INTO `provinces` VALUES ('37', '48', 'นครพนม', 'Nakhon Phanom');
INSERT INTO `provinces` VALUES ('38', '49', 'มุกดาหาร', 'Mukdahan');
INSERT INTO `provinces` VALUES ('39', '50', 'เชียงใหม่', 'Chiang Mai');
INSERT INTO `provinces` VALUES ('40', '51', 'ลำพูน', 'Lamphun');
INSERT INTO `provinces` VALUES ('41', '52', 'ลำปาง', 'Lampang');
INSERT INTO `provinces` VALUES ('42', '53', 'อุตรดิตถ์', 'Uttaradit');
INSERT INTO `provinces` VALUES ('43', '54', 'แพร่', 'Phrae');
INSERT INTO `provinces` VALUES ('44', '55', 'น่าน', 'Nan');
INSERT INTO `provinces` VALUES ('45', '56', 'พะเยา', 'Phayao');
INSERT INTO `provinces` VALUES ('46', '57', 'เชียงราย', 'Chiang Rai');
INSERT INTO `provinces` VALUES ('47', '58', 'แม่ฮ่องสอน', 'Mae Hong Son');
INSERT INTO `provinces` VALUES ('48', '60', 'นครสวรรค์', 'Nakhon Sawan');
INSERT INTO `provinces` VALUES ('49', '61', 'อุทัยธานี', 'Uthai Thani');
INSERT INTO `provinces` VALUES ('50', '62', 'กำแพงเพชร', 'Kamphaeng Phet');
INSERT INTO `provinces` VALUES ('51', '63', 'ตาก', 'Tak');
INSERT INTO `provinces` VALUES ('52', '64', 'สุโขทัย', 'Sukhothai');
INSERT INTO `provinces` VALUES ('53', '65', 'พิษณุโลก', 'Phitsanulok');
INSERT INTO `provinces` VALUES ('54', '66', 'พิจิตร', 'Phichit');
INSERT INTO `provinces` VALUES ('55', '67', 'เพชรบูรณ์', 'Phetchabun');
INSERT INTO `provinces` VALUES ('56', '70', 'ราชบุรี', 'Ratchaburi');
INSERT INTO `provinces` VALUES ('57', '71', 'กาญจนบุรี', 'Kanchanaburi');
INSERT INTO `provinces` VALUES ('58', '72', 'สุพรรณบุรี', 'Suphan Buri');
INSERT INTO `provinces` VALUES ('59', '73', 'นครปฐม', 'Nakhon Pathom');
INSERT INTO `provinces` VALUES ('60', '74', 'สมุทรสาคร', 'Samut Sakhon');
INSERT INTO `provinces` VALUES ('61', '75', 'สมุทรสงคราม', 'Samut Songkhram');
INSERT INTO `provinces` VALUES ('62', '76', 'เพชรบุรี', 'Phetchaburi');
INSERT INTO `provinces` VALUES ('63', '77', 'ประจวบคีรีขันธ์', 'Prachuap Khiri Khan');
INSERT INTO `provinces` VALUES ('64', '80', 'นครศรีธรรมราช', 'Nakhon Si Thammarat');
INSERT INTO `provinces` VALUES ('65', '81', 'กระบี่', 'Krabi');
INSERT INTO `provinces` VALUES ('66', '82', 'พังงา', 'Phang-nga');
INSERT INTO `provinces` VALUES ('67', '83', 'ภูเก็ต', 'Phuket');
INSERT INTO `provinces` VALUES ('68', '84', 'สุราษฎร์ธานี', 'Surat Thani');
INSERT INTO `provinces` VALUES ('69', '85', 'ระนอง', 'Ranong');
INSERT INTO `provinces` VALUES ('70', '86', 'ชุมพร', 'Chumphon');
INSERT INTO `provinces` VALUES ('71', '90', 'สงขลา', 'Songkhla');
INSERT INTO `provinces` VALUES ('72', '91', 'สตูล', 'Satun');
INSERT INTO `provinces` VALUES ('73', '92', 'ตรัง', 'Trang');
INSERT INTO `provinces` VALUES ('74', '93', 'พัทลุง', 'Phatthalung');
INSERT INTO `provinces` VALUES ('75', '94', 'ปัตตานี', 'Pattani');
INSERT INTO `provinces` VALUES ('76', '95', 'ยะลา', 'Yala');
INSERT INTO `provinces` VALUES ('77', '96', 'นราธิวาส', 'Narathiwat');
