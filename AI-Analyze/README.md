# Project My Note - AI Analysis Context

Thư mục này lưu ngữ cảnh phân tích sản phẩm và kỹ thuật cho các session AI/BA/Dev tiếp theo.

## Ý tưởng tổng quan

Xây dựng một website ghi chú cá nhân để vừa sử dụng thật, vừa tự học trọn vẹn quy trình phát triển phần mềm:

- Phân tích nghiệp vụ và thiết kế sản phẩm.
- Xây dựng website ghi chú cá nhân.
- Viết CI/CD bằng GitHub Actions.
- Viết Terraform để dựng hạ tầng.
- Triển khai ứng dụng lên môi trường thật.
- Theo dõi, cải tiến, mở rộng tính năng theo từng giai đoạn.

## Các module sản phẩm chính

- **Notes**: lưu và quản lý ghi chú cơ bản.
- **Goals**: tạo các mục tiêu linh hoạt như uống nước, tập thể dục, học tiếng Anh.
- **Goal Tracking**: ghi nhận tiến độ hằng ngày của từng mục tiêu.
- **Calendar**: xem lịch chung theo ngày, tuần, tháng; hiển thị lịch trình và trạng thái hoàn thành mục tiêu.

## Tài liệu trong thư mục

- [01-product-vision.md](01-product-vision.md): tầm nhìn, mục tiêu, đối tượng người dùng, giá trị sản phẩm.
- [02-functional-requirements.md](02-functional-requirements.md): yêu cầu chức năng theo module.
- [03-user-stories.md](03-user-stories.md): user stories và acceptance criteria ban đầu.
- [04-domain-model.md](04-domain-model.md): khái niệm nghiệp vụ, entity, quan hệ dữ liệu.
- [05-mvp-scope.md](05-mvp-scope.md): phạm vi MVP và các giai đoạn sau.
- [06-technical-learning-roadmap.md](06-technical-learning-roadmap.md): lộ trình học kỹ thuật từ app đến CI/CD, Terraform, deploy.
- [07-open-questions.md](07-open-questions.md): câu hỏi cần làm rõ trong các session sau.
- [08-architecture-decision.md](08-architecture-decision.md): quyết định kiến trúc ban đầu, gồm Vue.js cho frontend và Laravel cho backend.

## Nguyên tắc phân tích hiện tại

- Bắt đầu nhỏ, nhưng thiết kế để mở rộng.
- Ưu tiên chức năng có giá trị thật cho người dùng cá nhân.
- Biến mỗi tính năng thành cơ hội học một phần kỹ thuật.
- Không khóa chặt module "Mục tiêu" vào một loại cụ thể như uống nước; cần mô hình hóa linh hoạt.
- Calendar là nơi tổng hợp sự kiện, lịch trình và trạng thái goal, không chỉ là lịch hẹn.
- Tech stack đã chốt ban đầu: **Vue.js frontend + Laravel backend**.
