# UI Guide (Glass / Galaxy / Dark)

هذا الدليل يوضح نظام التصميم المركزي وطريقة استخدام المكوّنات بشكل موحّد داخل المشروع.

## 1) Design Tokens
- الملف المركزي: `assets/styles/tokens.css`
- يحتوي على:
  - **Colors**: الخلفيات الداكنة، النصوص، الألوان الدلالية (نجاح/تحذير/خطر/معلومة).
  - **Spacing**: سلم مسافات موحد (`--ui-space-*`).
  - **Radius**: أنصاف أقطار موحدة للعناصر.
  - **Shadows**: ظلال عادية + glow خفيف.
  - **Typography**: خطوط وأحجام نصوص موحدة مع دعم RTL.

## 2) Shared Components
- الملف المركزي: `assets/styles/components.css`
- المكوّنات المغطاة:
  - **Buttons**: `btn`, `btn-primary`, `btn-default`.
  - **Cards/Panels**: `card`, `panel`, `well`, `modal-content` بطابع زجاجي.
  - **Tables**: تنسيق رؤوس وصفوف وحدود متناسقة.
  - **Forms**: `form-control` + حالات focus واضحة.
  - **Alerts**: `alert-success`, `alert-danger`, `alert-warning`, `alert-info`.

## 3) Theme Integration
- تم ربط النظام في:
  - `themes/panel/nicex/header.twig`
  - `themes/admin/header.php`
- تم إضافة طبقة bridge في:
  - `css/panel/nicex/style.css`
  - `css/admin/style.css`

## 4) Migration Plan (Page-by-Page)
### المرحلة 1 (تم البدء)
- تحميل tokens/components في الهيدر العام للـ Panel و Admin.
- تفعيل التوحيد على العناصر الأساسية بدون كسر Bootstrap الحالي.

### المرحلة 2
- صفحات الأولوية العالية:
  1. Dashboard / New Order
  2. Orders / Services
  3. Add Funds / Payments
  4. Tickets
- إزالة أي inline styles واستبدالها بـ classes موحدة.

### المرحلة 3
- النوافذ المنبثقة والجداول المعقدة وواجهات الإدارة المتقدمة.
- تقليل اعتماد Bootstrap 3 classes المتعارضة تدريجيًا.

### المرحلة 4
- تنظيف نهائي:
  - حذف التكرارات.
  - توثيق كل component states.
  - تدقيق contrast و RTL/LTR على كل الصفحات.

## 5) قواعد سريعة
- لا تضف ألوان hardcoded قبل مراجعة tokens.
- أي مكوّن جديد يجب أن يعتمد على متغيرات `--ui-*`.
- حافظ على التباين وإمكانية القراءة في الخلفيات الداكنة.
- استخدم icons بدل emoji في الواجهات.
