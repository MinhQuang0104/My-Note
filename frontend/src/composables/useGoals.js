import { ref } from 'vue';

const goalsData = [
  {title:'Theo dõi uống nước hàng ngày', note:'Theo dõi lượng nước uống hằng ngày.', days:18, streak:7},
  {title:'Ôn tập Vue Composition API', note:'Duy trì một phiên ôn tập ngắn mỗi ngày.', days:12, streak:5},
  {title:'Đọc sách kỹ thuật', note:'Đọc và ghi lại một ý chính sau mỗi chương.', days:9, streak:3},
  {title:'Tập thể dục buổi sáng', note:'Vận động nhẹ để bắt đầu ngày tỉnh táo.', days:21, streak:10},
  {title:'Viết reflection cuối ngày', note:'Ghi lại điều đã học và điều cần cải thiện.', days:14, streak:4}
];

const schedulesData = {
  day:[['08:00','Đọc sách kỹ thuật','Chương 4 - Design patterns'],['18:00','Ôn tập Vue Composition API','Tiếp tục mục tiêu frontend'],['20:30','Viết reflection cuối ngày','Ghi lại 3 điều đã học']],
  month:[['Tuần 1','Ôn Vue API','4 phiên hoàn thành'],['Tuần 2','Đọc sách kỹ thuật','3 chương đã đọc'],['Tuần 3','Theo dõi uống nước','7 ngày liên tiếp']],
  year:[['T1',72],['T2',86],['T3',64],['T4',91],['T5',78],['T6',83],['T7',55],['T8',42],['T9',0],['T10',0],['T11',0],['T12',0]]
};

const recentNotesData = [
  { title: 'Ôn tập Vue Composition API', time: '10 phút trước', text: 'Các điểm cần nhớ khi dùng ref, computed và composable trong dự án.' },
  { title: 'Ý tưởng cho Project My-Note', time: 'Hôm qua', text: 'Tách luồng ghi chú, mục tiêu và lịch thành các nhịp làm việc rõ ràng.' },
];

export function useGoals() {
    return {
        goals: ref(goalsData),
        schedules: ref(schedulesData),
        notes: ref(recentNotesData)
    }
}