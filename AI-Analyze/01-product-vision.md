# 01. Product Vision

## Tên tạm thời

**Project My Note** - website ghi chú và theo dõi mục tiêu cá nhân.

## Vấn đề cần giải quyết

Người dùng muốn có một nơi riêng để:

- Ghi lại các ghi chú cá nhân hằng ngày.
- Đặt các mục tiêu lặp lại hoặc mục tiêu theo thời gian.
- Cập nhật tiến độ đơn giản, nhanh, không bị nặng nề.
- Xem tổng quan lịch trình, việc cần làm và trạng thái hoàn thành trên lịch chung.

Bên cạnh sản phẩm, đây còn là một dự án tự học để đi qua đầy đủ các bước: phân tích, phát triển, CI/CD, IaC, triển khai, vận hành.

## Đối tượng người dùng

### Primary user

Người dùng cá nhân, ban đầu chính là chủ dự án.

Nhu cầu:

- Quản lý ghi chú đơn giản.
- Theo dõi thói quen/mục tiêu cá nhân.
- Xem lịch ngày/tuần/tháng.
- Có sản phẩm để học full workflow phát triển phần mềm.

### Future users

Có thể mở rộng cho người dùng cá nhân khác nếu sản phẩm ổn định:

- Sinh viên.
- Người tự học.
- Người muốn theo dõi habit/mục tiêu cá nhân.
- Developer muốn một project mẫu để học DevOps/IaC.

## Giá trị cốt lõi

- **Đơn giản**: ghi chú và cập nhật tiến độ nhanh.
- **Linh hoạt**: mục tiêu không bị giới hạn vào một kiểu duy nhất.
- **Tổng quan**: lịch chung giúp nhìn ngày/tuần/tháng rõ ràng.
- **Học được thật**: kiến trúc, pipeline, hạ tầng và deploy đều được thực hành.

## Product statement

Project My Note là một website cá nhân giúp người dùng lưu ghi chú, đặt mục tiêu linh hoạt, ghi nhận tiến độ hằng ngày và xem tổng quan mọi thứ trên lịch chung; đồng thời dự án được xây dựng như một hành trình học full-stack/devops từ code đến triển khai.

## Nguyên tắc sản phẩm

- Mỗi tính năng nên có use case cá nhân rõ ràng.
- Nếu có thể làm đơn giản trước thì làm đơn giản, nhưng data model cần đủ khả năng mở rộng.
- Calendar nên là lớp hiển thị tổng hợp, không nên làm tất cả logic nằm trong Calendar.
- Goal nên là module riêng, có rule riêng, có log riêng.
- Notes nên độc lập với Goals, nhưng sau này có thể liên kết note với goal/event.

