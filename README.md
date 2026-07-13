# نظام إدارة عقارات مع إدارة العقود والpayments والتسويات
==========================

## Overview & Project Purpose

نظام إدارة عقارات مع إدارة العقود والpayments والتسويات هو تطبيق مفتوح المصدر يهدف إلى تسهيل إدارة العقارات وال العقود والpayments والتسويات. يهدف هذا النظام إلى توفير بيئة آمنة وموثوقة لتعامل مع العقارات وال العقود والpayments والتسويات.

### Project Purpose

* تسهيل إدارة العقارات وال العقود والpayments والتسويات
* توفير بيئة آمنة وموثوقة لتعامل مع العقارات وال العقود والpayments والتسويات
* تسهيل التعامل مع العقارات وال العقود والpayments والتسويات من خلال واجهة مستخدم سهلة الاستخدام

## Project Structure Mapping

* `docker/`: مجلد يحتوي على الملفات اللازمة لتشغيل النظام باستخدام docker-compose
* `src/`: مجلد يحتوي على الملفات المصدرية للنظام
* `src/app/`: مجلد يحتوي على الملفات المصدرية للنظام
* `src/app/models/`: مجلد يحتوي على الملفات المصدرية للنماذج البيانات
* `src/app/controllers/`: مجلد يحتوي على الملفات المصدرية للcontrollers
* `src/app/services/`: مجلد يحتوي على الملفات المصدرية للخدمات
* `src/app/repositories/`: مجلد يحتوي على الملفات المصدرية للrepositories
* `src/app/database/`: مجلد يحتوي على الملفات المصدرية للقاعدة البيانات
* `src/app/config/`: مجلد يحتوي على الملفات المصدرية للتنسيق
* `src/app/utils/`: مجلد يحتوي على الملفات المصدرية للوظائف الاضافية

## Step-by-Step Instructions on Running the Environment using docker-compose up

1. تأكد من أنك تمتلك docker-compose مثبتًا على جهازك.
2. افتح مجلد `docker/` في terminal.
3. استخدم الأمر التالي لتشغيل النظام باستخدام docker-compose:
bash
docker-compose up

4. بعد تشغيل النظام، يمكنك الوصول إلى واجهة المستخدم عبر `http://localhost:8080`
5. يمكنك استخدام أدوات مثل `docker-compose exec` لتنفيذ أوامر داخل container.

## Listing of Modules, Tables, and Roles

### Modules

* `عقارات`: يحتوي على جميع العقارات
* `العقود`: يحتوي على جميع العقود
* `payments`: يحتوي على جميع payments
* `التسويات`: يحتوي على جميع التسويات

### Tables

* `عقارات`:
 + `id`
 + `اسم العقار`
 + `وصف العقار`
 + `موقع العقار`
* `العقود`:
 + `id`
 + `اسم العقار`
 + `تاريخ بداية العقار`
 + `تاريخ نهاية العقار`
* `payments`:
 + `id`
 + `اسم العقار`
 + `تاريخ الدفع`
 + `مبلغ الدفع`
* `التسويات`:
 + `id`
 + `اسم العقار`
 + `تاريخ التسوية`
 + `مبلغ التسوية`

### Roles

* `admin`: يمتلك جميع الصلاحيات
* `user`: يمتلك صلاحيات محدودة

## Contact Developer Details

* **Developer Name**: [Developer Name]
* **Developer Email**: [Developer Email]
* **Developer Phone**: [Developer Phone]
* **Developer GitHub**: [Developer GitHub]

Note: Please replace the developer details with your own information.

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
