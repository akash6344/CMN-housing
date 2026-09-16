export const builder = {
  name: "Skyline Builders",
  role: "Premium Partner",
  initials: "SK",
};

export const navItems = [
  { id: "overview", label: "Overview", icon: "layout" },
  { id: "projects", label: "Projects", icon: "building" },
  { id: "listings", label: "Listings", icon: "home" },
  { id: "leads", label: "Leads", icon: "users" },
  { id: "bargain", label: "Smart Bargain", icon: "handshake" },
  { id: "ads", label: "Ads & Promotions", icon: "megaphone" },
  { id: "analytics", label: "Analytics", icon: "chart" },
  { id: "documents", label: "Documents", icon: "file" },
];

export const footItems = [
  { id: "notifications", label: "Notifications", icon: "bell" },
  { id: "settings", label: "Settings", icon: "settings" },
];

export const stats = [
  { label: "Active Projects", value: "8", trend: "+12% vs last month", dir: "up", icon: "building", tone: "mint" },
  { label: "Total Units Listed", value: "342", trend: "+8% vs last month", dir: "up", icon: "home", tone: "sky" },
  { label: "New Leads (Today)", value: "24", trend: "+15% vs last month", dir: "up", icon: "phone", tone: "green" },
  { label: "Active Bargain Deals", value: "12", trend: "-5% vs last month", dir: "down", icon: "handshake", tone: "peach" },
  { label: "Units Sold", value: "156", trend: "+23% vs last month", dir: "up", icon: "check", tone: "lime" },
  { label: "Ads Running", value: "5", trend: "0% vs last month", dir: "flat", icon: "megaphone", tone: "rose" },
];

export const leads = [
  {
    name: "Rahul Sharma",
    initials: "RS",
    color: "#dbeafe",
    ink: "#2563eb",
    property: "Sky Heights - 3BHK",
    status: "New",
    statusClass: "chip-new",
    email: "rahul.s****@gmail.com",
    phone: "+91 98****45678",
    place: "Mumbai, Maharashtra",
    when: "Today, 2:30 PM",
  },
  {
    name: "Priya Patel",
    initials: "PP",
    color: "#dcfce7",
    ink: "#16a34a",
    property: "Green Valley - 2BHK",
    status: "Contacted",
    statusClass: "chip-contacted",
    email: "priya.p****@yahoo.com",
    phone: "+91 87****12345",
    place: "Pune, Maharashtra",
    when: "Today, 11:15 AM",
  },
  {
    name: "Amit Kumar",
    initials: "AK",
    color: "#ffedd5",
    ink: "#ea580c",
    property: "Urban Edge - 4BHK",
    status: "Site Visit",
    statusClass: "chip-visit",
    email: "amit.k****@gmail.com",
    phone: "+91 99****67890",
    place: "Bangalore, Karnataka",
    when: "Yesterday, 4:00 PM",
  },
];

export const activity = [
  { icon: "userPlus", tone: "mint", title: "New lead received", desc: "Rahul Sharma inquired about Sky Heights 3BHK", time: "2 minutes ago" },
  { icon: "send", tone: "sky", title: "Counter offer sent", desc: "You countered ₹1.62 Cr for Sky Heights A-1204", time: "1 hour ago" },
  { icon: "check", tone: "lime", title: "Unit sold!", desc: "Green Valley B-302 marked as sold", time: "3 hours ago" },
  { icon: "file", tone: "peach", title: "Pending approval", desc: "Urban Edge Phase 2 awaiting admin review", time: "5 hours ago" },
  { icon: "alert", tone: "rose", title: "Ad campaign expiring", desc: "Featured listing for Sky Heights expires in 2 days", time: "Yesterday" },
];

export const deals = [
  {
    id: "B001",
    title: "Sky Heights Tower A",
    unit: "Unit SH-A-1204",
    status: "Counter Sent",
    statusClass: "chip-counter",
    buyer: "₹1.55 Cr",
    counter: "₹1.62 Cr",
  },
  {
    id: "B002",
    title: "Green Valley Block B",
    unit: "Unit GV-B-505",
    status: "Awaiting Response",
    statusClass: "chip-wait",
    buyer: "₹85.00 L",
    counter: null,
    expires: "Expires in 4 hours",
  },
];

export const hotUnits = [
  { name: "Sky Heights A-1204", meta: "234 views • 12 inquiries", rank: 1 },
  { name: "Green Valley B-505", meta: "189 views • 8 inquiries", rank: 2 },
  { name: "Urban Edge C-1801", meta: "156 views • 6 inquiries", rank: 3 },
];
