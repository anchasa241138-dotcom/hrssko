<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ระบบการจ้างทางเลือก - สสจ.ศรีสะเกษ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                    },
                    colors: {
                        moph: {
                            light: '#059669', // emerald-600
                            DEFAULT: '#064e3b', // emerald-900
                            dark: '#022c22', // emerald-950
                            gold: '#fbbf24', // amber-400 for accents
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Prompt', sans-serif; }
        .bg-gradient-moph {
            background: linear-gradient(135deg, #065f46 0%, #022c22 100%);
        }
        
        /* View switching (Auth vs App) */
        .view-section { display: none !important; }
        .view-section.active { display: flex !important; animation: fadeIn 0.4s ease-in-out; }

        /* Auth Tabs */
        .auth-tab { display: none; }
        .auth-tab.active { display: block; animation: fadeIn 0.4s ease-in-out; }

        /* App Tabs */
        .app-tab-content { display: none; }
        .app-tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }

        .sidebar-item { transition: all 0.3s ease; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); transform: translateX(5px); }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #fff; color: white; }
        
        /* Table Styles */
        .table-container { overflow-x: auto; }
        .complex-table th, .complex-table td { border: 1px solid #e5e7eb; padding: 0.75rem; vertical-align: middle; }
        .complex-table th { background-color: #f3f4f6; font-weight: 600; text-align: center; color: #374151; white-space: nowrap; }
        .complex-table td { font-size: 0.875rem; color: #4b5563; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">

    <!-- ======================= AUTH VIEW (CENTERED CARD) ======================= -->
    <div id="view-auth" class="view-section active h-full w-full relative flex items-center justify-center p-4" style="background: linear-gradient(135deg, #065f46 0%, #022c22 100%);">
        
        <!-- Auth Container -->
        <div id="auth-container" class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-white/20 transition-all duration-500">
            
            <!-- Header (Gradient) -->
            <div class="bg-gradient-moph px-8 py-8 text-center text-white relative">
                <!-- Decorative subtle shapes inside header -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-green-900 rounded-full blur-2xl"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center mb-3 border-2 border-moph-gold shadow-lg">
                        <i class="fas fa-hospital-user text-3xl text-moph-gold"></i>
                    </div>
                    <h1 class="text-2xl font-bold mb-1 drop-shadow-md tracking-wide">ระบบการจ้างทางเลือก</h1>
                    <p class="text-moph-gold text-sm font-medium tracking-wide">สำนักงานสาธารณสุขจังหวัดศรีสะเกษ</p>
                </div>
            </div>

            <!-- Form Area -->
            <div class="p-8">
                <!-- Tab Nav -->
                <div class="flex bg-gray-100 p-1.5 rounded-xl mb-6 max-w-md mx-auto">
                    <button onclick="switchAuthTab('login')" id="tab-btn-login" class="flex-1 py-2 text-center text-sm font-semibold text-moph bg-white shadow-sm rounded-lg transition-all">
                        เข้าสู่ระบบ
                    </button>
                    <button onclick="switchAuthTab('register')" id="tab-btn-register" class="flex-1 py-2 text-center text-sm font-semibold text-gray-500 hover:text-gray-700 rounded-lg transition-all">
                        สมัครสมาชิก
                    </button>
                </div>

                <!-- Login Form -->
                <div id="auth-login" class="auth-tab active max-w-md mx-auto">
                    <form onsubmit="handleLogin(event)">
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-600 mb-2">ชื่อผู้ใช้งาน</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="login-username" type="text" required class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 focus:border-moph transition-all text-sm" placeholder="Username">
                            </div>
                        </div>
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-600 mb-2">รหัสผ่าน</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="login-password" type="password" required class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 focus:border-moph transition-all text-sm" placeholder="Password">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gradient-moph hover:brightness-110 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200/50 transition duration-300 transform hover:-translate-y-0.5">
                            เข้าสู่ระบบ
                        </button>
                        <div class="text-center mt-6">
                            <a href="#" class="text-sm text-gray-400 hover:text-moph transition">ลืมรหัสผ่านใช่หรือไม่?</a>
                        </div>
                    </form>
                </div>

                <!-- Register Form -->
                <div id="auth-register" class="auth-tab">
                    <form onsubmit="handleRegister(event)">
                        <div class="space-y-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div class="col-span-1 md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-600 mb-1">คำนำหน้า</label>
                                    <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm">
                                        <option value="" disabled selected>เลือก</option>
                                        <option>นาย</option>
                                        <option>นาง</option>
                                        <option>นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อ</label>
                                    <input type="text" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm" placeholder="ชื่อจริง">
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-600 mb-1">นามสกุล</label>
                                    <input type="text" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm" placeholder="นามสกุล">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">ตำแหน่ง</label>
                                    <input type="text" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm" placeholder="ระบุตำแหน่ง">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">ประเภทการจ้าง</label>
                                    <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm">
                                        <option value="" disabled selected>เลือกประเภท</option>
                                        <option>ข้าราชการ</option>
                                        <option>พนักงานราชการ</option>
                                        <option>ลูกจ้างประจำ</option>
                                        <option>พนักงานกระทรวงสาธารณสุข</option>
                                        <option>ลูกจ้างชั่วคราว(รายเดือน)</option>
                                        <option>รายวัน/รายคาบ/จ้างเหมา</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">สังกัดหน่วยงาน</label>
                                    <select id="reg-agency" onchange="handleAgencyChange()" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm">
                                        <option value="" disabled selected>เลือกหน่วยงาน</option>
                                        <option value="สสจ.ศรีสะเกษ">สสจ.ศรีสะเกษ</option>
                                        <option value="สสอ.">สสอ.</option>
                                        <option value="รพท.">รพท.</option>
                                        <option value="รพช.">รพช.</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">อำเภอ</label>
                                    <select id="reg-district" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-500">
                                        <option value="" disabled selected>เลือกอำเภอ</option>
                                        <option value="เมืองศรีสะเกษ">เมืองศรีสะเกษ</option><option value="ยางชุมน้อย">ยางชุมน้อย</option><option value="กันทรารมย์">กันทรารมย์</option>
                                        <option value="กันทรลักษ์">กันทรลักษ์</option><option value="ขุขันธ์">ขุขันธ์</option><option value="ไพรบึง">ไพรบึง</option>
                                        <option value="ปรางค์กู่">ปรางค์กู่</option><option value="ขุนหาญ">ขุนหาญ</option><option value="ราษีไศล">ราษีไศล</option>
                                        <option value="อุทุมพรพิสัย">อุทุมพรพิสัย</option><option value="บึงบูรพ์">บึงบูรพ์</option><option value="ห้วยทับทัน">ห้วยทับทัน</option>
                                        <option value="โนนคูณ">โนนคูณ</option><option value="ศรีรัตนะ">ศรีรัตนะ</option><option value="น้ำเกลี้ยง">น้ำเกลี้ยง</option>
                                        <option value="วังหิน">วังหิน</option><option value="ภูสิงห์">ภูสิงห์</option><option value="เมืองจันทร์">เมืองจันทร์</option>
                                        <option value="เบญจลักษ์">เบญจลักษ์</option><option value="พยุห์">พยุห์</option><option value="โพธิ์ศรีสุวรรณ">โพธิ์ศรีสุวรรณ</option>
                                        <option value="ศิลาลาด">ศิลาลาด</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm" placeholder="08X-XXX-XXXX">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">รหัสผ่าน</label>
                                    <input type="password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-moph/50 transition-all text-sm" placeholder="อย่างน้อย 8 ตัวอักษร">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3.5 rounded-xl shadow-lg transition duration-300 transform hover:-translate-y-0.5 mt-2">
                            ลงทะเบียนผู้ใช้ใหม่
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- ======================= APP VIEW ======================= -->
    <div id="view-app" class="view-section h-full w-full flex">
        
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-moph text-white shadow-xl flex flex-col transition-all duration-300 flex-shrink-0">
            <div class="p-6 text-center border-b border-white/10">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-gray-50 to-gray-200 rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-moph-gold">
                    <i class="fas fa-hospital text-4xl text-moph-dark"></i>
                </div>
                <h1 class="text-xl font-bold leading-tight tracking-wide text-white drop-shadow-sm">ระบบการจ้างทางเลือก</h1>
                <p class="text-sm text-moph-gold mt-1 font-medium tracking-wider">สสจ.ศรีสะเกษ</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="#" onclick="switchAppTab('tab-dashboard', this)" class="sidebar-item active flex items-center px-6 py-3 text-white">
                            <i class="fas fa-chart-line w-6"></i>
                            <span>แดชบอร์ดอัตรากำลังคน</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="switchAppTab('tab-vacant', this)" class="sidebar-item flex items-center px-6 py-3 text-green-50">
                            <i class="fas fa-chair w-6"></i>
                            <span>การบริหารตำแหน่งว่าง</span>
                        </a>
                    </li>
                    <!-- Mock menus -->
                    <li><a href="#" onclick="switchAppTab('tab-staff-moph', this, 'พนักงานราชการ')" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-tie w-6"></i><span>พนักงานราชการ</span></a></li>
                    <li><a href="#" onclick="switchAppTab('tab-staff-moph', this, 'ลูกจ้างประจำ')" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user w-6"></i><span>ลูกจ้างประจำ</span></a></li>
                    <li><a href="#" onclick="switchAppTab('tab-staff-moph', this, 'พนักงานกระทรวงสาธารณสุข')" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-md w-6"></i><span>พนักงานกระทรวงสาธารณสุข</span></a></li>
                    <li><a href="#" onclick="switchAppTab('tab-staff-moph', this, 'ลูกจ้างชั่วคราว (รายเดือน)')" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-clock w-6"></i><span>ลูกจ้างชั่วคราว (รายเดือน)</span></a></li>
                    <li><a href="#" onclick="switchAppTab('tab-staff-moph', this, 'ลูกจ้างชั่วคราวรายวัน/จ้างเหมา')" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-users w-6"></i><span class="text-sm">ลูกจ้างชั่วคราวรายวัน/จ้างเหมา</span></a></li>
                    <li><a href="#" onclick="switchAppTab('tab-movement', this)" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-exchange-alt w-6"></i><span>ความเคลื่อนไหวตำแหน่ง</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-bell w-6 relative"><span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full h-3 w-3 flex items-center justify-center">3</span></i><span>แจ้งเตือนใบประกอบฯ</span></a></li>
                </ul>
            </nav>
            
            <div class="p-4 border-t border-white/20">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <p class="font-medium">ผู้ดูแลระบบ</p>
                        <p class="text-xs text-green-200 mb-1">งานบริหารทรัพยากรบุคคล</p>
                        <button onclick="handleLogout()" class="text-xs text-white bg-red-500 hover:bg-red-600 px-2 py-1 rounded inline-block transition">
                            <i class="fas fa-sign-out-alt mr-1"></i> ออกจากระบบ
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10 flex-shrink-0">
                <h2 id="page-title" class="text-2xl font-bold text-gray-800">แดชบอร์ดแสดงสถานการณ์อัตรากำลังคน</h2>
                <div class="flex items-center gap-4">
                    <button class="text-gray-500 hover:text-moph transition">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    <button class="text-gray-500 hover:text-moph transition relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
                
                <!-- APP TAB 1: DASHBOARD -->
                <div id="tab-dashboard" class="app-tab-content active">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-moph flex items-center justify-between hover:shadow-md transition">
                            <div><p class="text-sm text-gray-500 mb-1">บุคลากรทั้งหมด (คน)</p><h3 class="text-3xl font-bold text-gray-800">1,245</h3></div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-moph"><i class="fas fa-users text-xl"></i></div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between hover:shadow-md transition">
                            <div><p class="text-sm text-gray-500 mb-1">ตำแหน่งว่าง (อัตรา)</p><h3 class="text-3xl font-bold text-gray-800">32</h3></div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500"><i class="fas fa-chair text-xl"></i></div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 flex items-center justify-between hover:shadow-md transition">
                            <div><p class="text-sm text-gray-500 mb-1">พนักงาน กสธ. (คน)</p><h3 class="text-3xl font-bold text-gray-800">458</h3></div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600"><i class="fas fa-user-md text-xl"></i></div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500 flex items-center justify-between hover:shadow-md transition">
                            <div><p class="text-sm text-gray-500 mb-1">ใบประกอบฯ ใกล้หมดอายุ</p><h3 class="text-3xl font-bold text-gray-800">7</h3></div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">สัดส่วนอัตรากำลังคนจำแนกตามประเภท</h4>
                            <div class="h-64 bg-gray-50 rounded border border-gray-100 flex items-center justify-center">
                                <span class="text-gray-400">พื้นที่สำหรับแสดงกราฟ (Chart Area)</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">แจ้งเตือนล่าสุด</h4>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg border border-red-100">
                                    <i class="fas fa-id-card text-red-500 mt-1"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">นางสาวสมหญิง ใจดี</p>
                                        <p class="text-xs text-gray-500">ใบประกอบวิชาชีพพยาบาลจะหมดอายุในอีก 15 วัน</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- APP TAB 2: VACANT POSITIONS -->
                <div id="tab-vacant" class="app-tab-content">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">รายการตำแหน่งว่างทั้งหมด</h3>
                        <button onclick="openAddModal()" class="bg-moph hover:bg-moph-dark text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2"> <i class="fas fa-plus"></i> เพิ่มข้อมูลตำแหน่งว่าง </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 table-container">
                        <table class="w-full complex-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="w-16">ลำดับ</th>
                                    <th rowspan="2">คำนำหน้า</th>
                                    <th rowspan="2">อำเภอ</th>
                                    <th rowspan="2">รพ.สต.</th>
                                    <th rowspan="2">ตำแหน่ง<br>เลขที่</th>
                                    <th rowspan="2">ตำแหน่ง<br>สายงาน</th>
                                    <th rowspan="2">ประเภทเจ้าหน้าที่</th>
                                    <th rowspan="2">วันที่ตำแหน่งว่าง</th>
                                    <th rowspan="2">จำนวน<br>วันที่ว่าง</th>
                                      <th rowspan="2">สถานะการ<br>ดำเนินการ</th>
                                      <th rowspan="2">เขต<br>อนุมัติ</th>
                                      <th rowspan="2">สป.<br>อนุมัติ</th>
                                      <th rowspan="2">สสจ.<br>อนุมัติ</th>
                                    <th colspan="6">ขอเปลี่ยนตำแหน่ง/หน่วยงาน/ประเภทการจ้าง</th>
                                    <th rowspan="2">จัดการ</th>
                                </tr>
                                <tr>
                                    <th>ตำแหน่ง<br>เลขที่ใหม่</th>
                                    <th>เปลี่ยน<br>ตำแหน่งใหม่</th>
                                    <th>เปลี่ยน<br>ประเภทใหม่</th>
                                    <th>เปลี่ยน<br>หน่วยงานใหม่</th>
                                    <th>สถานะเงื่อนไข<br>ตำแหน่ง</th>
                                    <th>วันที่มีผล<br>บังคับใช้</th>
                                </tr>
                            </thead>
                            <tbody id="vacant-table-body">
                                
                                  <tr class="hover:bg-gray-50 transition" data-opstatus="อัตราว่าง" data-opresult="-">
                                      <td class="text-center">1</td>
                                      <td>รพท.</td>
                                      <td>เมืองศรีสะเกษ</td>
                                      <td>-</td>
                                      <td class="text-center">685412</td>
                                      <td>พยาบาลวิชาชีพ</td>
                                      <td>พนักงาน กสธ.</td>
                                      <td class="text-center">15 ส.ค. 2568</td>
                                      <td class="text-center">45 วัน</td>
                                      <td class="text-center"><div class="leading-tight">อัตราว่าง</div></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">ว่างเดิม</td>
                                      <td class="text-center">1 ต.ค. 2568</td>
                                      <td class="text-center">
                                          <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editVacantPosition(this)"><i class="fas fa-edit"></i></button>
                                          <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove(); saveToLocalStorage();"><i class="fas fa-trash"></i></button>
                                      </td>
                                  </tr>
                                  <tr class="hover:bg-gray-50 transition" data-opstatus="หน่วยงานขอสรรหาทดแทน" data-opresult="รอพิจารณา">
                                      <td class="text-center">2</td>
                                      <td>รพ.สต.</td>
                                      <td>ขุขันธ์</td>
                                      <td>รพ.สต.บ้านตาอุด</td>
                                      <td class="text-center">112233</td>
                                      <td>นักวิชาการสาธารณสุข</td>
                                      <td>พนักงานกระทรวงสาธารณสุข</td>
                                      <td class="text-center">1 ก.ย. 2569</td>
                                      <td class="text-center">1 เดือน 3 วัน</td>
                                      <td class="text-center"><div class="leading-tight">หน่วยงานขอสรรหาทดแทน<div class="text-xs text-yellow-600 font-medium mt-1"><i class="fas fa-clock"></i> รอพิจารณา</div></div></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">ว่างเดิม</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">
                                          <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editVacantPosition(this)"><i class="fas fa-edit"></i></button>
                                          <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove(); saveToLocalStorage();"><i class="fas fa-trash"></i></button>
                                      </td>
                                  </tr>
                                  <tr class="hover:bg-gray-50 transition" data-opstatus="นพ.สสจ. พิจารณาผลการสรรหา" data-opresult="ผ่าน">
                                      <td class="text-center">3</td>
                                      <td>รพช.</td>
                                      <td>ราษีไศล</td>
                                      <td>-</td>
                                      <td class="text-center">998877</td>
                                      <td>เภสัชกร</td>
                                      <td>ลูกจ้างชั่วคราว</td>
                                      <td class="text-center">15 ก.ค. 2569</td>
                                      <td class="text-center">2 เดือน 19 วัน</td>
                                      <td class="text-center"><div class="leading-tight">นพ.สสจ. พิจารณาผลการสรรหา<div class="text-xs text-green-600 font-medium mt-1"><i class="fas fa-check-circle"></i> ผ่าน</div></div></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">ว่างเดิม</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">
                                          <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editVacantPosition(this)"><i class="fas fa-edit"></i></button>
                                          <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove(); saveToLocalStorage();"><i class="fas fa-trash"></i></button>
                                      </td>
                                  </tr>
                                  <tr class="hover:bg-gray-50 transition" data-opstatus="ไม่มีผู้สมัคร/ไม่มีผู้ผ่านการคัดเลือก/เปลี่ยนเงื่อนไข" data-opresult="ไม่ผ่าน">
                                      <td class="text-center">4</td>
                                      <td>สสอ.</td>
                                      <td>ขุนหาญ</td>
                                      <td>-</td>
                                      <td class="text-center">554433</td>
                                      <td>เจ้าพนักงานสาธารณสุข</td>
                                      <td>พนักงานกระทรวงสาธารณสุข</td>
                                      <td class="text-center">10 มิ.ย. 2569</td>
                                      <td class="text-center">3 เดือน 24 วัน</td>
                                      <td class="text-center"><div class="leading-tight">ไม่มีผู้สมัคร/ไม่มีผู้ผ่านการคัดเลือก/เปลี่ยนเงื่อนไข<div class="text-xs text-red-600 font-medium mt-1"><i class="fas fa-times-circle"></i> ไม่ผ่าน</div></div></td>
                                      <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">ว่างเดิม</td>
                                      <td class="text-center">-</td>
                                      <td class="text-center">
                                          <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editVacantPosition(this)"><i class="fas fa-edit"></i></button>
                                          <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove(); saveToLocalStorage();"><i class="fas fa-trash"></i></button>
                                      </td>
                                  </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

<!-- APP TAB 3: STAFF MOPH -->
                  <div id="tab-staff-moph" class="app-tab-content h-full hidden">
                      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                          <div class="flex items-center justify-between mb-4">
                              <h3 class="text-lg font-bold text-gray-700">รายชื่อพนักงานกระทรวงสาธารณสุขทั้งหมด</h3>
                              <div class="flex items-center gap-2">
                                  <label class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition shadow-sm text-sm font-medium cursor-pointer" title="นำเข้าข้อมูลจาก Excel">
                                      <i class="fas fa-file-excel mr-1"></i> นำเข้า Excel
                                      <input type="file" accept=".xlsx, .xls, .csv" class="hidden" onchange="importStaffExcel(this)">
                                  </label>
                                  <button onclick="exportStaffExcel()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm text-sm font-medium" title="ดาวน์โหลดเป็น Excel">
                                      <i class="fas fa-download mr-1"></i> ส่งออก Excel
                                  </button>
                                  <button onclick="openStaffModal()" class="bg-moph text-white px-4 py-2 rounded-md hover:bg-moph/90 transition shadow-sm text-sm font-medium">
                                      <i class="fas fa-plus mr-1"></i> เพิ่มข้อมูลพนักงาน
                                  </button>
                              </div>
                          </div>
                          
                          <div class="overflow-x-auto overflow-y-auto flex-1 rounded-lg border border-gray-200">
                              <table class="w-full text-sm text-left whitespace-nowrap">
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10">
                                       <tr>
                                           <th class="px-3 py-3 border-b border-r text-center">ลำดับ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ตำแหน่งเลขที่</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สถานะตำแหน่ง</th>
                                           <th class="px-3 py-3 border-b border-r text-center">อำเภอ จ.18</th>
                                           <th class="px-3 py-3 border-b border-r text-center">คำนำหน้าหน่วยงาน จ.18</th>
                                           <th class="px-3 py-3 border-b border-r text-center">หน่วยงาน จ.18</th>
                                           <th class="px-3 py-3 border-b border-r text-center">กลุ่มงาน จ.18</th>
                                           <th class="px-3 py-3 border-b border-r text-center">หน่วยงานภายใน จ.18</th>
                                           <th class="px-3 py-3 border-b border-r text-center">อำเภอ (จริง)</th>
                                           <th class="px-3 py-3 border-b border-r text-center">คำนำหน้าหน่วยงาน (จริง)</th>
                                           <th class="px-3 py-3 border-b border-r text-center">หน่วยงาน (จริง)</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ตำแหน่งสายงาน</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ประเภทตำแหน่ง</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ระดับ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ประเภทเจ้าหน้าที่</th>
                                           <th class="px-3 py-3 border-b border-r text-center">คำนำหน้า</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ชื่อ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">นามสกุล</th>
                                           <th class="px-3 py-3 border-b border-r text-center">เลขประจำตัวประชาชน</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สถานะการปฏิบัติราชการปัจจุบัน</th>
                                           <th class="px-3 py-3 border-b border-r text-center">วัน เดือน ปี ที่บรรจุ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">วุฒิการศึกษาสูงสุด</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สาขาวิชาเอกของวุฒิสูงสุด</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สาขาวุฒิบัตรความเชี่ยวชาญของวุฒิสูงสุด</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สถาบันการศึกษาของวุฒิสูงสุด</th>
                                           <th class="px-3 py-3 border-b border-r text-center">วันที่สำเร็จการศึกษาของวุฒิสูงสุด</th>
                                           <th class="px-3 py-3 border-b border-r text-center">เกรดเฉลี่ย</th>
                                           <th class="px-3 py-3 border-b border-r text-center">เลขใบประกอบ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">ใบประกอบ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">วันออกใบประกอบ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">วันหมดอายุ</th>
                                           <th class="px-3 py-3 border-b border-r text-center">สถาบันออกใบประกอบ</th>
                                           <th class="px-3 py-3 border-b text-center sticky right-0 bg-gray-100 shadow-sm">จัดการ</th>
                                       </tr>
                                   </thead>
                                  <tbody id="staff-moph-table-body">
                                      <!-- Dummy Row -->
                                      <tr class="hover:bg-gray-50 transition">
                                          <td class="text-center sm-row-num">1</td>
                                          <td>รพ.สต.</td>
                                          <td>เมือง</td>
                                          <td>บ้านหนอง</td>
                                          <td>บริการ</td>
                                          <td>พยาบาล</td>
                                          <td class="text-center">ตรง จ.</td>
                                          <td class="text-center">98765</td>
                                          <td class="text-center">ปฏิบัติการ</td>
                                          <td>พยาบาลวิชาชีพ</td>
                                          <td class="text-center">มีคนครอง</td>
                                          <td>พนักงานกระทรวงสาธารณสุข</td>
                                          <td>นางสาว</td>
                                          <td>สมหญิง</td>
                                          <td>ใจดี</td>
                                          <td class="text-center">1234567890123</td>
                                          <td class="text-center">1 ม.ค. 2565</td>
                                          <td>ป.ตรี</td>
                                          <td class="text-center">1 พ.ค. 2564</td>
                                          <td class="text-center">3.50</td>
                                          <td>พยาบาลศาสตร์</td>
                                          <td class="text-center">998877</td>
                                          <td class="text-center">1 มิ.ย. 2564</td>
                                          <td class="text-center">1 มิ.ย. 2569</td>
                                          <td class="text-center sticky right-0 bg-white">
                                              <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editStaffMoph(this)"><i class="fas fa-edit"></i></button>
                                              <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>

                <!-- APP TAB 4: POSITION MOVEMENT (ความเคลื่อนไหวตำแหน่ง) -->
                <div id="tab-movement" class="app-tab-content">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-exchange-alt text-moph"></i> ความเคลื่อนไหวตำแหน่ง (Position Movements)
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">บันทึกและติดตามประวัติการเคลื่อนไหวตำแหน่ง การโอนย้าย การปรับเปลี่ยนสถานะ และการสรรหาทดแทน</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openAddMovementModal()" class="bg-moph hover:bg-moph-dark text-white px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2 text-sm font-medium">
                                <i class="fas fa-plus"></i> บันทึกความเคลื่อนไหว
                            </button>
                            <button onclick="exportMovementExcel()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2 text-sm font-medium">
                                <i class="fas fa-file-excel text-green-600"></i> ส่งออก Excel
                            </button>
                        </div>
                    </div>

                    <!-- Movement Stat Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">ความเคลื่อนไหวทั้งหมด</p>
                                <h4 id="stat-movement-total" class="text-2xl font-bold text-gray-800">0</h4>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">เปลี่ยนเป็นตำแหน่งว่าง</p>
                                <h4 id="stat-movement-vacant" class="text-2xl font-bold text-red-600">0</h4>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">บรรจุ / รับโอนใหม่</p>
                                <h4 id="stat-movement-newhire" class="text-2xl font-bold text-emerald-600">0</h4>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fas fa-random"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">ขอเปลี่ยนตำแหน่ง/สังกัด</p>
                                <h4 id="stat-movement-transfer" class="text-2xl font-bold text-amber-600">0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Filter & Search Bar -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-[280px]">
                            <div class="relative w-full max-w-md">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                                <input type="text" id="movement-search" onkeyup="filterMovementTable()" placeholder="ค้นหาตำแหน่งเลขที่, สายงาน, หรือหน่วยงาน..." class="w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-moph focus:border-moph">
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500 font-medium">ประเภทการเคลื่อนไหว:</label>
                            <select id="movement-type-filter" onchange="filterMovementTable()" class="border border-gray-300 rounded-lg text-sm px-3 py-1.5 focus:ring-1 focus:ring-moph focus:border-moph">
                                <option value="">ทั้งหมด</option>
                                <option value="เปลี่ยนสถานะเป็นตำแหน่งว่าง">เปลี่ยนสถานะเป็นตำแหน่งว่าง</option>
                                <option value="บรรจุใหม่ / ทำสัญญาจ้าง">บรรจุใหม่ / ทำสัญญาจ้าง</option>
                                <option value="รับโอน / ย้าย">รับโอน / ย้าย</option>
                                <option value="ขอเปลี่ยนตำแหน่ง/หน่วยงาน">ขอเปลี่ยนตำแหน่ง/หน่วยงาน</option>
                                <option value="ลาออก / สิ้นสุดสัญญา">ลาออก / สิ้นสุดสัญญา</option>
                                <option value="เลื่อนระดับ">เลื่อนระดับ</option>
                            </select>
                        </div>
                    </div>

                    <!-- Movement Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 table-container overflow-x-auto">
                        <table class="w-full complex-table whitespace-nowrap text-sm">
                            <thead>
                                <tr>
                                    <th class="w-16 text-center">ลำดับ</th>
                                    <th class="text-center">วันที่บันทึก</th>
                                    <th class="text-center">ตำแหน่งเลขที่</th>
                                    <th>ตำแหน่งสายงาน</th>
                                    <th>ประเภทเจ้าหน้าที่</th>
                                    <th>สังกัดหน่วยงาน (จ.18)</th>
                                    <th>อำเภอ</th>
                                    <th class="text-center">ประเภทความเคลื่อนไหว</th>
                                    <th>รายละเอียด / สาเหตุ</th>
                                    <th>คำสั่ง / หนังสืออ้างอิง</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="movement-table-body">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    
                  <!-- ======================= MODAL FORM ======================= -->
    <div id="dataModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-11/12 max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">บันทึกข้อมูลตำแหน่งว่าง</h3>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                <form class="space-y-6">
                    <!-- Section 1 -->
                    <div>
                        <h4 class="text-moph font-semibold border-b pb-2 mb-4"><i class="fas fa-info-circle mr-2"></i> ข้อมูลพื้นฐานตำแหน่ง</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">คำนำหน้า</label>
                                <select id="modal_prefix" onchange="handleModalPrefixChange()" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                                    <option value="" disabled selected>เลือกคำนำหน้า</option>
                                    <option value="รพท.">รพท.</option>
                                    <option value="รพช.">รพช.</option>
                                    <option value="สสอ.">สสอ.</option>
                                    <option value="รพ.สต.">รพ.สต.</option>
                                    <option value="สสจ.">สสจ.</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">อำเภอ</label>
                                <select id="modal_district" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph"><option value="" disabled selected>เลือกอำเภอ</option>
                                    <option value="เมืองศรีสะเกษ">เมืองศรีสะเกษ</option>
                                    <option value="ยางชุมน้อย">ยางชุมน้อย</option>
                                    <option value="กันทรารมย์">กันทรารมย์</option>
                                    <option value="กันทรลักษ์">กันทรลักษ์</option>
                                    <option value="ขุขันธ์">ขุขันธ์</option>
                                    <option value="ไพรบึง">ไพรบึง</option>
                                    <option value="ปรางค์กู่">ปรางค์กู่</option>
                                    <option value="ขุนหาญ">ขุนหาญ</option>
                                    <option value="ราษีไศล">ราษีไศล</option>
                                    <option value="อุทุมพรพิสัย">อุทุมพรพิสัย</option>
                                    <option value="บึงบูรพ์">บึงบูรพ์</option>
                                    <option value="ห้วยทับทัน">ห้วยทับทัน</option>
                                    <option value="โนนคูณ">โนนคูณ</option>
                                    <option value="ศรีรัตนะ">ศรีรัตนะ</option>
                                    <option value="น้ำเกลี้ยง">น้ำเกลี้ยง</option>
                                    <option value="วังหิน">วังหิน</option>
                                    <option value="ภูสิงห์">ภูสิงห์</option>
                                    <option value="เมืองจันทร์">เมืองจันทร์</option>
                                    <option value="เบญจลักษ์">เบญจลักษ์</option>
                                    <option value="พยุห์">พยุห์</option>
                                    <option value="โพธิ์ศรีสุวรรณ">โพธิ์ศรีสุวรรณ</option>
                                    <option value="ศิลาลาด">ศิลาลาด</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">รพ.สต.</label>
                                <input type="text" id="modal_rpst" disabled class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งเลขที่</label><input type="text" id="modal_position_no" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งสายงาน</label><input type="text" id="modal_position_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทเจ้าหน้าที่</label><select id="modal_emp_type" onchange="handleEmpTypeChange()" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                                    <option>พนักงานราชการ</option>
                                    <option>ลูกจ้างประจำ</option>
                                    <option>พนักงานกระทรวงสาธารณสุข</option>
                                    <option>ลูกจ้างชั่วคราว</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ตำแหน่งว่าง</label>
                                <input type="date" id="modal_vacant_date" onchange="calculateVacantDays()" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนวันที่ว่าง</label>
                                <input type="text" id="modal_vacant_days_display" readonly class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-600 focus:outline-none cursor-not-allowed" placeholder="คำนวณอัตโนมัติ">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">สถานะการดำเนินการ</label>
                                <select id="modal_operation_status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    <option value="" disabled selected>เลือกสถานะการดำเนินการ</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ผลการพิจารณา</label>
                                <select id="modal_operation_result" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    <option value="-">รอพิจารณา</option>
                                    <option value="ผ่าน">ผ่าน</option>
                                    <option value="ไม่ผ่าน">ไม่ผ่าน</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h4 class="text-moph font-semibold border-b pb-2 mb-4"><i class="fas fa-check-circle mr-2"></i> สถานะการอนุมัติ</h4>
                        <div class="space-y-3">
                            <!-- สสจ. -->
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 hover:border-moph/50 transition-colors">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="chk_ssj" onchange="toggleApprovalSection('ssj')" class="rounded text-moph focus:ring-moph w-4 h-4 cursor-pointer">
                                    <span class="text-sm font-semibold text-gray-700">สสจ.อนุมัติแล้ว</span>
                                </label>
                                <div id="section_ssj" class="hidden mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 border-t border-gray-200 pt-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เลขที่หนังสือ</label>
                                        <input type="text" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">วันที่อนุมัติ</label>
                                        <input type="date" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เอกสารแนบ (.pdf)</label>
                                        <div class="flex items-center gap-2">
                                            <label class="flex-1 bg-white border border-gray-300 rounded-md px-2 py-1.5 text-sm text-center cursor-pointer hover:bg-gray-100 transition text-gray-600 whitespace-nowrap overflow-hidden text-ellipsis shadow-sm" title="คลิกเพื่อเลือกไฟล์">
                                                <span class="file-label-text"><i class="fas fa-upload mr-1 text-gray-400"></i> อัพโหลด</span>
                                                <input type="file" accept=".pdf" class="hidden" onchange="handleFileUpload(this)">
                                            </label>
                                            <button type="button" class="bg-white text-gray-600 border border-gray-300 rounded-md px-3 py-1.5 text-sm hover:bg-gray-100 transition shadow-sm btn-download-file" title="ดาวน์โหลดไฟล์" onclick="downloadMockFile(this)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CHRO เขต 10 -->
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 hover:border-moph/50 transition-colors">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="chk_chro" onchange="toggleApprovalSection('chro')" class="rounded text-moph focus:ring-moph w-4 h-4 cursor-pointer">
                                    <span class="text-sm font-semibold text-gray-700">CHRO เขต 10 อนุมัติแล้ว</span>
                                </label>
                                <div id="section_chro" class="hidden mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 border-t border-gray-200 pt-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เลขที่หนังสือ</label>
                                        <input type="text" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">วันที่อนุมัติ</label>
                                        <input type="date" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เอกสารแนบ (.pdf)</label>
                                        <div class="flex items-center gap-2">
                                            <label class="flex-1 bg-white border border-gray-300 rounded-md px-2 py-1.5 text-sm text-center cursor-pointer hover:bg-gray-100 transition text-gray-600 whitespace-nowrap overflow-hidden text-ellipsis shadow-sm" title="คลิกเพื่อเลือกไฟล์">
                                                <span class="file-label-text"><i class="fas fa-upload mr-1 text-gray-400"></i> อัพโหลด</span>
                                                <input type="file" accept=".pdf" class="hidden" onchange="handleFileUpload(this)">
                                            </label>
                                            <button type="button" class="bg-white text-gray-600 border border-gray-300 rounded-md px-3 py-1.5 text-sm hover:bg-gray-100 transition shadow-sm btn-download-file" title="ดาวน์โหลดไฟล์" onclick="downloadMockFile(this)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- อ.ก.พ. สป.สธ. -->
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 hover:border-moph/50 transition-colors">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="chk_akp" onchange="toggleApprovalSection('akp')" class="rounded text-moph focus:ring-moph w-4 h-4 cursor-pointer">
                                    <span class="text-sm font-semibold text-gray-700">อ.ก.พ. สป.สธ.อนุมัติแล้ว</span>
                                </label>
                                <div id="section_akp" class="hidden mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 border-t border-gray-200 pt-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เลขที่หนังสือ</label>
                                        <input type="text" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">วันที่อนุมัติ</label>
                                        <input type="date" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-moph">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">เอกสารแนบ (.pdf)</label>
                                        <div class="flex items-center gap-2">
                                            <label class="flex-1 bg-white border border-gray-300 rounded-md px-2 py-1.5 text-sm text-center cursor-pointer hover:bg-gray-100 transition text-gray-600 whitespace-nowrap overflow-hidden text-ellipsis shadow-sm" title="คลิกเพื่อเลือกไฟล์">
                                                <span class="file-label-text"><i class="fas fa-upload mr-1 text-gray-400"></i> อัพโหลด</span>
                                                <input type="file" accept=".pdf" class="hidden" onchange="handleFileUpload(this)">
                                            </label>
                                            <button type="button" class="bg-white text-gray-600 border border-gray-300 rounded-md px-3 py-1.5 text-sm hover:bg-gray-100 transition shadow-sm btn-download-file" title="ดาวน์โหลดไฟล์" onclick="downloadMockFile(this)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h4 class="text-blue-700 font-semibold mb-4"><i class="fas fa-exchange-alt mr-2"></i> ขอเปลี่ยนตำแหน่ง/หน่วยงาน/ประเภทการจ้าง</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งเลขที่ใหม่</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เปลี่ยนตำแหน่งใหม่</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เปลี่ยนประเภทใหม่</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เปลี่ยนหน่วยงานใหม่</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">สถานะเงื่อนไขตำแหน่ง</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่มีผลบังคับใช้</label>
                                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                <button onclick="toggleModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">ยกเลิก</button>
                <button type="button" onclick="saveVacantPosition()" class="px-4 py-2 bg-moph text-white rounded-md hover:bg-moph-dark transition shadow">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>

    <!-- ======================= SCRIPTS ======================= -->
    <script>
        // === View Switching (Auth vs App) ===
                async function handleLogin(e) {
            e.preventDefault();
            const user = document.getElementById('login-username').value;
            const pass = document.getElementById('login-password').value;
            
            // Bypass fetch completely for admin
            if (user === 'admin' && pass === 'admin1234') {
                document.getElementById('view-auth').classList.remove('active');
                document.getElementById('view-app').classList.add('active');
                e.target.reset();
                return;
            }
            
            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ username: user, password: pass })
                });
                const data = await res.json();
                
                if (data.success) {
                    document.getElementById('view-auth').classList.remove('active');
                    document.getElementById('view-app').classList.add('active');
                    e.target.reset();
                } else {
                    alert(data.message || 'ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง!');
                }
            } catch (err) {
                alert('Server error: ไม่สามารถเชื่อมต่อฐานข้อมูลได้ (ใช้ admin/admin1234 เพื่อทดสอบข้ามการเชื่อมต่อฐานข้อมูล)');
            }
        }

        async function handleLogout() {
            await fetch('/api/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }});
            document.getElementById('view-app').classList.remove('active');
            document.getElementById('view-auth').classList.add('active');
            switchAuthTab('login');
        }

        // === Auth Tabs (Login / Register) ===
        function switchAuthTab(tab) {
            document.getElementById('auth-login').classList.remove('active');
            document.getElementById('auth-register').classList.remove('active');
            
            document.getElementById('tab-btn-login').className = "flex-1 py-3 text-center font-semibold text-gray-400 border-b-2 border-transparent hover:text-gray-600 transition-colors";
            document.getElementById('tab-btn-register').className = "flex-1 py-3 text-center font-semibold text-gray-400 border-b-2 border-transparent hover:text-gray-600 transition-colors";
            
            if(tab === 'login') {
                document.getElementById('auth-login').classList.add('active');
                document.getElementById('tab-btn-login').className = "flex-1 py-3 text-center font-semibold text-moph border-b-2 border-moph transition-colors";
            } else {
                document.getElementById('auth-register').classList.add('active');
                document.getElementById('tab-btn-register').className = "flex-1 py-3 text-center font-semibold text-moph border-b-2 border-moph transition-colors";
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const form = e.target;
            const inputs = form.querySelectorAll('input, select');
            
            const payload = {
                prefix: inputs[0].value,
                first_name: inputs[1].value,
                last_name: inputs[2].value,
                position: inputs[3].value,
                employment_type: inputs[4].value,
                agency: inputs[5].value,
                district: inputs[6].value,
                phone: inputs[7].value,
                password: inputs[8].value,
                username: inputs[7].value, // ใช้เบอร์โทรเป็น Username สำหรับตัวอย่าง
            };

            try {
                const res = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if (data.success) {
                    alert('ลงทะเบียนสำเร็จ! ตอนนี้คุณเข้าสู่ระบบแล้ว');
                    document.getElementById('view-auth').classList.remove('active');
                    document.getElementById('view-app').classList.add('active');
                    form.reset();
                } else {
                    alert('ไม่สามารถลงทะเบียนได้ (อาจมีบัญชีนี้แล้ว)');
                }
            } catch (err) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }

        function saveVacantPosition() {
            const prefix = document.getElementById('modal_prefix') ? document.getElementById('modal_prefix').value : '-';
            const district = document.getElementById('modal_district') ? document.getElementById('modal_district').value : '-';
            const rpst = document.getElementById('modal_rpst') ? document.getElementById('modal_rpst').value : '-';
            const positionNo = document.getElementById('modal_position_no') ? document.getElementById('modal_position_no').value : '-';
            const positionName = document.getElementById('modal_position_name') ? document.getElementById('modal_position_name').value : '-';
            const empType = document.getElementById('modal_emp_type') ? document.getElementById('modal_emp_type').value : '-';
            const vacantDate = document.getElementById('modal_vacant_date') ? document.getElementById('modal_vacant_date').value : '-';
            const vacantDays = document.getElementById('modal_vacant_days_display') ? document.getElementById('modal_vacant_days_display').value : '-';
            const opStatusVal = document.getElementById('modal_operation_status') ? document.getElementById('modal_operation_status').value : '';
            const opResultVal = document.getElementById('modal_operation_result') ? document.getElementById('modal_operation_result').value : '-';

            const ssj = document.getElementById('chk_ssj') && document.getElementById('chk_ssj').checked;
            const chro = document.getElementById('chk_chro') && document.getElementById('chk_chro').checked;
            const akp = document.getElementById('chk_akp') && document.getElementById('chk_akp').checked;

              const sectSsj = document.getElementById('section_ssj');
              const ssjDoc = sectSsj.querySelector('input[type="text"]').value;
              const ssjDate = sectSsj.querySelector('input[type="date"]').value;
              const ssjFileInput = sectSsj.querySelector('input[type="file"]');
              const ssjFileUrl = ssjFileInput.getAttribute('data-fileurl') || '';
              const ssjFileName = ssjFileInput.getAttribute('data-filename') || '';
              const ssjFile = ssjFileName !== '';

              const sectChro = document.getElementById('section_chro');
              const chroDoc = sectChro.querySelector('input[type="text"]').value;
              const chroDate = sectChro.querySelector('input[type="date"]').value;
              const chroFileInput = sectChro.querySelector('input[type="file"]');
              const chroFileUrl = chroFileInput.getAttribute('data-fileurl') || '';
              const chroFileName = chroFileInput.getAttribute('data-filename') || '';
              const chroFile = chroFileName !== '';

              const sectAkp = document.getElementById('section_akp');
              const akpDoc = sectAkp.querySelector('input[type="text"]').value;
              const akpDate = sectAkp.querySelector('input[type="date"]').value;
              const akpFileInput = sectAkp.querySelector('input[type="file"]');
              const akpFileUrl = akpFileInput.getAttribute('data-fileurl') || '';
              const akpFileName = akpFileInput.getAttribute('data-filename') || '';
              const akpFile = akpFileName !== '';
    

            const formatIcon = (isChecked, hasFile) => {
                if (!isChecked) return '-';
                if (hasFile) {
                    return '<div class="flex items-center justify-center gap-2"><i class="fas fa-check text-green-500"></i><button type="button" onclick="downloadFromTable(this)" class="text-gray-400 hover:text-green-600 transition" title="ดาวน์โหลดเอกสารแนบ"><i class="fas fa-download text-xs"></i></button></div>';
                }
                return '<i class="fas fa-check text-green-500"></i>';
            };

            const tbody = document.getElementById('vacant-table-body');
            if(!tbody) return;

            const rowCount = editingRow ? editingRow.querySelector('td').innerText : (tbody.querySelectorAll('tr').length + 1);
            
            let formattedDate = vacantDate;
            if(vacantDate && vacantDate !== '-') {
                const d = new Date(vacantDate);
                const thaiMonths = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
                if(!isNaN(d)) {
                    formattedDate = `${d.getDate()} ${thaiMonths[d.getMonth()]} ${d.getFullYear() + 543}`;
                }
            }

            let tr;
            if (editingRow) {
                tr = editingRow;
            } else {
                tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition';
                tbody.appendChild(tr);
            }
            
            tr.setAttribute('data-opstatus', opStatusVal || '');
            tr.setAttribute('data-opresult', opResultVal || '-');
            
            tr.setAttribute('data-ssj-doc', ssjDoc);
            tr.setAttribute('data-ssj-date', ssjDate);
            tr.setAttribute('data-ssj-fileurl', ssjFileUrl);
            tr.setAttribute('data-ssj-filename', ssjFileName);
            
            tr.setAttribute('data-chro-doc', chroDoc);
            tr.setAttribute('data-chro-date', chroDate);
            tr.setAttribute('data-chro-fileurl', chroFileUrl);
            tr.setAttribute('data-chro-filename', chroFileName);
            
            tr.setAttribute('data-akp-doc', akpDoc);
            tr.setAttribute('data-akp-date', akpDate);
            tr.setAttribute('data-akp-fileurl', akpFileUrl);
            tr.setAttribute('data-akp-filename', akpFileName);

            tr.innerHTML = `
                <td class="text-center">${rowCount}</td>
                <td>${prefix || '-'}</td>
                <td>${district || '-'}</td>
                <td>${prefix === 'รพ.สต.' ? rpst : '-'}</td>
                <td class="text-center">${positionNo || '-'}</td>
                <td>${positionName || '-'}</td>
                <td>${empType || '-'}</td>
                <td class="text-center">${formattedDate || '-'}</td>
                <td class="text-center">${vacantDays || '-'}</td>
                <td class="text-center">${formatOperationStatus(opStatusVal, opResultVal)}</td>
                <td class="text-center">${formatIcon(chro, chroFile)}</td>
                <td class="text-center">${formatIcon(akp, akpFile)}</td>
                <td class="text-center">${formatIcon(ssj, ssjFile)}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">
                    <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" onclick="editVacantPosition(this)"><i class="fas fa-edit"></i></button>
                    <button class="text-red-500 hover:text-red-700" onclick="this.closest('tr').remove(); saveToLocalStorage();"><i class="fas fa-trash"></i></button>
                </td>
            `;

            toggleModal();
            editingRow = null;
            saveToLocalStorage();
            
            const form = document.querySelector('#dataModal form');
            if(form) form.reset();
            handleModalPrefixChange();
            handleEmpTypeChange();
            toggleApprovalSection('ssj');
            toggleApprovalSection('chro');
            toggleApprovalSection('akp');
        }

        function handleEmpTypeChange() {
            const empType = document.getElementById('modal_emp_type').value;
            const opStatus = document.getElementById('modal_operation_status');
            
            if (!opStatus) return;

            const currentValue = opStatus.value;
            opStatus.innerHTML = '<option value="" disabled selected>เลือกสถานะการดำเนินการ</option>';
            
            if (empType === 'พนักงานกระทรวงสาธารณสุข') {
                const options = [
                    "อัตราว่าง",
                    "หน่วยงานขอสรรหาทดแทน",
                    "ขอเปลี่ยนตำแหน่ง/ประเภทการว่าง/หน่วยงาน",
                    "หน่วยงานส่งร่างประกาศรับสมัครฯ",
                    "เสนอ นพ.สสจ. ลงนามประกาศรับสมัครฯ",
                    "เผยแพร่/ประชาสัมพันธ์ประกาศรับสมัคร/สรรหา",
                    "หน่วยงานรายงานผลการสรรหา",
                    "นพ.สสจ. พิจารณาผลการสรรหา",
                    "เรียกรายงานตัว/ทำสัญญาจ้าง",
                    "ส่งสัญญาจ้างให้ สสจ.",
                    "นพ.สสจ. ลงนามสัญญา/คำสั่งจ้าง",
                    "บันทึกในระบบ HROPH",
                    "ไม่มีผู้สมัคร/ไม่มีผู้ผ่านการคัดเลือก/เปลี่ยนเงื่อนไข"
                ];
                
                options.forEach(opt => {
                    const el = document.createElement('option');
                    el.value = opt;
                    el.innerText = opt;
                    opStatus.appendChild(el);
                });
                opStatus.disabled = false;
                if(document.getElementById('modal_operation_result')) document.getElementById('modal_operation_result').disabled = false;
                
                if (options.includes(currentValue)) {
                    opStatus.value = currentValue;
                }
            } else {
                opStatus.disabled = true;
                if(document.getElementById('modal_operation_result')) document.getElementById('modal_operation_result').disabled = true;
            }
        }
        let editingRow = null;

        
        function saveToLocalStorage() {
            const tbody = document.getElementById('vacant-table-body');
            if (tbody) {
                const clone = tbody.cloneNode(true);
                clone.querySelectorAll('tr[data-source="staff-unoccupied"]').forEach(el => el.remove());
                localStorage.setItem('vacant_table_data', clone.innerHTML);
            }
        }

        async function loadFromLocalStorage() {
            const tbody = document.getElementById('vacant-table-body');
            if (!tbody) return;
            const savedData = localStorage.getItem('vacant_table_data');
            if (savedData) {
                tbody.innerHTML = savedData;
            }

            try {
                const res = await fetch('/api/moph-staff');
                if (res.ok) {
                    const allStaff = await res.json();
                    const unoccupiedStaff = allStaff.filter(s => (s.position_status || '').trim() === 'ไม่มีคนครอง');
                    
                    // Remove existing dynamically synced unoccupied rows to prevent duplication
                    tbody.querySelectorAll('tr[data-source="staff-unoccupied"]').forEach(r => r.remove());
                    
                    unoccupiedStaff.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-amber-50/40 transition bg-amber-50/20';
                        tr.setAttribute('data-source', 'staff-unoccupied');
                        tr.setAttribute('data-staff-id', item.id);
                        tr.setAttribute('data-opstatus', 'ไม่มีคนครอง');
                        tr.setAttribute('data-opresult', '-');
                        
                        const prefix = item.agency_prefix || item.actual_prefix || '-';
                        const district = item.agency_district || item.actual_district || '-';
                        const rpst = item.agency_name || item.agency_internal || item.actual_name || '-';
                        const posNo = item.position_number || '-';
                        const posLine = item.position_line || '-';
                        const empType = item.staff_type || '-';
                        const vDate = item.hire_date ? formatDateToThai(item.hire_date) : '-';
                        
                        tr.innerHTML = `
                            <td class="text-center font-medium text-gray-500"></td>
                            <td>${prefix}</td>
                            <td>${district}</td>
                            <td>${rpst}</td>
                            <td class="text-center font-semibold text-moph">${posNo}</td>
                            <td>${posLine}</td>
                            <td><span class="inline-block px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700 font-medium">${empType}</span></td>
                            <td class="text-center">${vDate}</td>
                            <td class="text-center">-</td>
                            <td class="text-center">
                                <div class="leading-tight">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 shadow-sm">
                                        <i class="fas fa-user-slash mr-1"></i>ไม่มีคนครอง
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center text-xs text-gray-500">ว่างเดิม</td>
                            <td class="text-center">-</td>
                            <td class="text-center">
                                <button type="button" class="text-blue-500 hover:text-blue-700 mr-2" title="แก้ไขข้อมูลตำแหน่งนี้" onclick='editStaffMophById(${item.id}, ${JSON.stringify(item).replace(/'/g, "&#39;").replace(/"/g, "&quot;")})'><i class="fas fa-edit"></i></button>
                                <button type="button" class="text-red-500 hover:text-red-700" title="ลบข้อมูล" onclick="deleteStaffById(${item.id})"><i class="fas fa-trash"></i></button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                    
                    // Re-calculate row index numbers
                    let idx = 1;
                    tbody.querySelectorAll('tr').forEach(r => {
                        const firstTd = r.querySelector('td');
                        if (firstTd) firstTd.innerText = idx++;
                    });
                }
            } catch (err) {
                console.error('Error fetching unoccupied positions:', err);
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            // Force reset storage once so the user sees the new mock rows
            if (!localStorage.getItem('mock_rows_initialized_v3')) {
                localStorage.removeItem('vacant_table_data');
                localStorage.setItem('mock_rows_initialized_v3', 'true');
            }
            loadFromLocalStorage();
            loadStaffFromStorage();
        });


        
        function handleFileUpload(input) {
            const labelSpan = input.closest('label').querySelector('.file-label-text');
            const downloadBtn = input.closest('.flex').querySelector('.btn-download-file');
            if (input.files && input.files.length > 0) {
                const fileName = input.files[0].name;
                labelSpan.innerHTML = '<i class="fas fa-file-pdf mr-1 text-red-500"></i> ' + fileName;
                // Store fake file url for mockup
                const objectUrl = URL.createObjectURL(input.files[0]);
                input.setAttribute('data-fileurl', objectUrl);
                input.setAttribute('data-filename', fileName);
                downloadBtn.classList.remove('text-gray-600');
                downloadBtn.classList.add('text-green-600');
            } else {
                labelSpan.innerHTML = '<i class="fas fa-upload mr-1 text-gray-400"></i> อัพโหลด';
                input.removeAttribute('data-fileurl');
                input.removeAttribute('data-filename');
                downloadBtn.classList.add('text-gray-600');
                downloadBtn.classList.remove('text-green-600');
            }
        }

        function downloadMockFile(btn) {
            const input = btn.closest('.flex').querySelector('input[type="file"]');
            const fileUrl = input.getAttribute('data-fileurl');
            const fileName = input.getAttribute('data-filename');
            
            if (fileUrl) {
                // Create a temporary anchor to trigger download
                const a = document.createElement('a');
                a.href = fileUrl;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } else {
                alert('โปรดเลือกไฟล์ (อัพโหลด) ก่อนทำการดาวน์โหลดครับ');
            }
        }
        
        // Mock table download function
        function downloadFromTable(btn) {
            // สร้างไฟล์ PDF ตัวอย่างขนาดเล็กมาก (Dummy PDF) เพื่อจำลองการดาวน์โหลดจากตาราง
            const dummyPdfBase64 = 'JVBERi0xLjQKJcOkw7zDtsOfCjIgMCBvYmoKPDwvTGVuZ3RoIDMgMCBSL0ZpbHRlci9GbGF0ZURlY29kZT4+CnN0cmVhbQp4nDPQM1Qo5ypUMFAwALJMLU31jBQsTAz1LBSKUrnCtRTy0yuLQExghrK+iZ6Bvpm+iYGlvkGagb65vpG+oYm+pb6ZgQGXYy0XFwB19A+tCmVuZHN0cmVhbQplbmRvYmoKCjMgMCBvYmoKNTQKZW5kb2JqCgo0IDAgb2JqCjw8L1R5cGUvUGFnZS9NZWRpYUJveFswIDAgNTk1IDg0Ml0vUmVzb3VyY2VzPDwvRm9udDw8L0YxIDEgMCBSPj4+Pi9Db250ZW50cyAyIDAgUi9QYXJlbnQgNSAwIFI+PgplbmRvYmoKCjEgMCBvYmoKPDwvVHlwZS9Gb250L1N1YnR5cGUvVHlwZTEvQmFzZUZvbnQvSGVsdmV0aWNhPj4KZW5kb2JqCgo1IDAgb2JqCjw8L1R5cGUvUGFnZXMvQ291bnQgMS9LaWRzWzQgMCBSXT4+CmVuZG9iagoKNiAwIG9iago8PC9UeXBlL0NhdGFsb2cvUGFnZXMgNSAwIFI+PgplbmRvYmoKCjcgMCBvYmoKPDwvUHJvZHVjZXIoZ2hvc3RzY3JpcHQpL0NyZWF0aW9uRGF0ZShEOjIwMjExMTE4MTExMjAzKzAzJzAwJyk+PgplbmRvYmoKCnhyZWYKMCA4CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDI1OCAwMDAwMCBuIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDAxNDAgMDAwMDAgbiAKMDAwMDAwMDE1OSAwMDAwMCBuIAowMDAwMDAwMzQ2IDAwMDAwIG4gCjAwMDAwMDA0MDMgMDAwMDAgbiAKMDAwMDAwMDQ1MiAwMDAwMCBuIAp0cmFpbGVyCjw8L1NpemUgOC9Sb290IDYgMCBSL0luZm8gNyAwIFI+PgpzdGFydHhyZWYKNTE5CiUlRU9GCg==';
            const a = document.createElement('a');
            a.href = 'data:application/pdf;base64,' + dummyPdfBase64;
            a.download = 'เอกสารแนบตัวอย่าง.pdf';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        
        let editingStaffRow = null;

        function toggleStaffModal() {
            const modal = document.getElementById('staffModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        
        function exportStaffExcel() {
            const headers = [
                "ลำดับ", "ตำแหน่งเลขที่", "สถานะตำแหน่ง", "อำเภอ จ.18", "คำนำหน้าหน่วยงาน จ.18",
                "หน่วยงาน จ.18", "กลุ่มงาน จ.18", "หน่วยงานภายใน จ.18",
                "อำเภอ (จริง)", "คำนำหน้าหน่วยงาน (จริง)", "หน่วยงาน (จริง)",
                "ตำแหน่งสายงาน", "ประเภทตำแหน่ง", "ระดับ", "ประเภทเจ้าหน้าที่", "คำนำหน้า", "ชื่อ", "นามสกุล",
                "เลขประจำตัวประชาชน", "สถานะการปฏิบัติราชการปัจจุบัน", "วัน เดือน ปี ที่บรรจุ", "วุฒิการศึกษาสูงสุด", "สาขาวิชาเอกของวุฒิสูงสุด",
                "สาขาวุฒิบัตรความเชี่ยวชาญของวุฒิสูงสุด", "สถาบันการศึกษาของวุฒิสูงสุด", "วันที่สำเร็จการศึกษาของวุฒิสูงสุด", "เกรดเฉลี่ย",
                "เลขใบประกอบ", "ใบประกอบ", "วันออกใบประกอบ", "วันหมดอายุ", "สถาบันออกใบประกอบ"
            ];
            
            let csvContent = "\uFEFF" + headers.join(",") + "\n";
            const tbody = document.getElementById("staff-moph-table-body");
            const rows = tbody.querySelectorAll("tr");
            
            rows.forEach(row => {
                if (row.innerText.includes("กำลังโหลดข้อมูล") || row.innerText.includes("ยังไม่มีข้อมูล")) return;
                const cols = row.querySelectorAll("td");
                if (cols.length < 32) return;
                
                let rowData = [];
                for (let i = 0; i < 32; i++) {
                    let cellData = cols[i].innerText.trim();
                    if (i === 18 || i === 27) { // เลขบัตรประชาชน หรือ เลขใบประกอบ
                        cellData = '="' + cellData + '"';
                    } else if (cellData.includes(",") || cellData.includes('"') || cellData.includes("\n")) {
                        cellData = '"' + cellData.replace(/"/g, '""') + '"';
                    }
                    rowData.push(cellData);
                }
                csvContent += rowData.join(",") + "\n";
            });

            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = (window.currentStaffType || "ข้อมูลพนักงาน") + ".csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        
        let editingStaffId = null;

        async function loadStaffFromStorage() {
            const tbody = document.getElementById('staff-moph-table-body');
            if (!tbody) return;
            const type = window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข';
            tbody.innerHTML = '<tr><td colspan="25" class="text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin text-2xl mb-2 text-moph block"></i>กำลังดึงข้อมูล ' + type + ' จากฐานข้อมูลส่วนกลาง...</td></tr>';
            
            try {
                const res = await fetch('/api/moph-staff');
                if (!res.ok) throw new Error('API request failed');
                let allStaff = await res.json();
                
                // Filter by selected staff type and position_status is NOT 'ไม่มีคนครอง'
                const staffList = allStaff.filter(s => (s.staff_type || '').trim() === type.trim() && (s.position_status || '').trim() !== 'ไม่มีคนครอง');
                
                if (staffList.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="33" class="text-center py-10 text-gray-400 font-medium"><i class="fas fa-inbox text-3xl mb-2 text-gray-300 block"></i>ยังไม่มีข้อมูล ' + type + ' ในฐานข้อมูล</td></tr>';
                    return;
                }
                
                tbody.innerHTML = '';
                staffList.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 transition';
                    tr.innerHTML = `
                        <td class="text-center sm-row-num">${index + 1}</td>
                        <td class="text-center">${item.position_number || '-'}</td>
                        <td class="text-center">${item.position_status || '-'}</td>
                        <td class="text-center">${item.agency_district || '-'}</td>
                        <td class="text-center">${item.agency_prefix || '-'}</td>
                        <td class="text-center">${item.agency_name || '-'}</td>
                        <td class="text-center">${item.agency_group || '-'}</td>
                        <td class="text-center">${item.agency_internal || '-'}</td>
                        <td class="text-center">${item.actual_district || '-'}</td>
                        <td class="text-center">${item.actual_prefix || '-'}</td>
                        <td class="text-center">${item.actual_name || '-'}</td>
                        <td class="text-center">${item.position_line || '-'}</td>
                        <td class="text-center">${item.position_type || '-'}</td>
                        <td class="text-center">${item.position_level || '-'}</td>
                        <td class="text-center font-semibold text-gray-700">${item.staff_type || type}</td>
                        <td class="text-center">${item.personal_prefix || '-'}</td>
                        <td class="text-center">${item.personal_fname || '-'}</td>
                        <td class="text-center">${item.personal_lname || '-'}</td>
                        <td class="text-center">${item.personal_id_card || '-'}</td>
                        <td class="text-center">${item.duty_status || '-'}</td>
                        <td class="text-center">${item.hire_date ? formatDateToThai(item.hire_date) : '-'}</td>
                        <td class="text-center">${item.hire_qual || '-'}</td>
                        <td class="text-center">${item.major || '-'}</td>
                        <td class="text-center">${item.specialist_cert || '-'}</td>
                        <td class="text-center">${item.institution || '-'}</td>
                        <td class="text-center">${item.grad_date ? formatDateToThai(item.grad_date) : '-'}</td>
                        <td class="text-center">${item.gpa || '-'}</td>
                        <td class="text-center">${item.license_no || '-'}</td>
                        <td class="text-center">${item.license_name || '-'}</td>
                        <td class="text-center">${item.license_issue ? formatDateToThai(item.license_issue) : '-'}</td>
                        <td class="text-center">${item.license_expire ? formatDateToThai(item.license_expire) : '-'}</td>
                        <td class="text-center">${item.license_org || '-'}</td>
                        <td class="text-center sticky right-0 bg-white shadow-sm border-l border-gray-200">
                            <button type="button" class="text-blue-500 hover:text-blue-700 mx-1" onclick="editStaffMophById(${item.id}, ${JSON.stringify(item).replace(/"/g, '&quot;')})"><i class="fas fa-edit"></i></button>
                            <button type="button" class="text-red-500 hover:text-red-700 mx-1" onclick="deleteStaffById(${item.id})"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (err) {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="25" class="text-center py-6 text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>เกิดข้อผิดพลาดในการโหลดข้อมูลจากฐานข้อมูล</td></tr>';
            }
        }

        function openStaffModal() {
            editingStaffId = null;
            const formInputs = document.querySelectorAll('#staffModal input:not([readonly]), #staffModal select');
            formInputs.forEach(el => {
                if (el.tagName === 'SELECT') {
                    if (el.id === 'sm_pers_title') el.value = 'นาย';
                    else if (el.id === 'sm_pos_status') el.value = 'มีคนครอง';
                    else el.value = '';
                } else {
                    el.value = '';
                }
            });
            const stInput = document.getElementById('sm_staff_type');
            if (stInput) stInput.value = window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข';
            const modalTitle = document.querySelector('#staffModal h3.text-xl');
            if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-user-md text-moph mr-2"></i>บันทึกข้อมูล' + (window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข');
            toggleStaffModal();
        }

        function editStaffMophById(id, item) {
            editingStaffId = id;
            const setVal = (fieldId, val) => {
                const el = document.getElementById(fieldId);
                if (el) el.value = (val === '-' || !val) ? '' : val;
            };

            setVal('sm_pos_no', item.position_number);
            setVal('sm_pos_status', item.position_status);
            setVal('sm_agency_district', item.agency_district);
            setVal('sm_agency_prefix', item.agency_prefix);
            setVal('sm_agency_name', item.agency_name);
            setVal('sm_agency_group', item.agency_group);
            setVal('sm_agency_internal', item.agency_internal);
            setVal('sm_actual_district', item.actual_district);
            setVal('sm_actual_prefix', item.actual_prefix);
            setVal('sm_actual_name', item.actual_name);
            setVal('sm_pos_name', item.position_line);
            setVal('sm_pos_type', item.position_type);
            setVal('sm_pos_level', item.position_level);
            setVal('sm_staff_type', item.staff_type || window.currentStaffType);

            setVal('sm_pers_title', item.personal_prefix || 'นาย');
            setVal('sm_pers_fname', item.personal_fname);
            setVal('sm_pers_lname', item.personal_lname);
            setVal('sm_pers_idcard', item.personal_id_card);
            setVal('sm_duty_status', item.duty_status);

            setVal('sm_pers_startdate', item.hire_date);
            setVal('sm_pers_degree', item.hire_qual);
            setVal('sm_major', item.major);
            setVal('sm_specialist_cert', item.specialist_cert);
            setVal('sm_institution', item.institution);
            setVal('sm_pers_graddate', item.grad_date);
            setVal('sm_pers_gpa', item.gpa);

            setVal('sm_lic_no', item.license_no);
            setVal('sm_lic_name', item.license_name);
            setVal('sm_lic_issue', item.license_issue);
            setVal('sm_lic_expire', item.license_expire);
            setVal('sm_lic_org', item.license_org);

            const modalTitle = document.querySelector('#staffModal h3.text-xl');
            const currentType = item.staff_type || window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข';
            if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-user-md text-moph mr-2"></i>แก้ไขข้อมูล' + currentType;

            toggleStaffModal();
        }

        async function saveStaffMoph() {
            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value : '';

            const payload = {
                position_number: getVal('sm_pos_no'),
                position_status: getVal('sm_pos_status'),
                agency_district: getVal('sm_agency_district'),
                agency_prefix: getVal('sm_agency_prefix'),
                agency_name: getVal('sm_agency_name'),
                agency_group: getVal('sm_agency_group'),
                agency_internal: getVal('sm_agency_internal'),
                actual_district: getVal('sm_actual_district'),
                actual_prefix: getVal('sm_actual_prefix'),
                actual_name: getVal('sm_actual_name'),
                position_line: getVal('sm_pos_name'),
                position_type: getVal('sm_pos_type'),
                position_level: getVal('sm_pos_level'),
                staff_type: getVal('sm_staff_type') || window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข',
                personal_prefix: getVal('sm_pers_title'),
                personal_fname: getVal('sm_pers_fname'),
                personal_lname: getVal('sm_pers_lname'),
                personal_id_card: getVal('sm_pers_idcard'),
                duty_status: getVal('sm_duty_status'),
                hire_date: getVal('sm_pers_startdate'),
                hire_qual: getVal('sm_pers_degree'),
                major: getVal('sm_major'),
                specialist_cert: getVal('sm_specialist_cert'),
                institution: getVal('sm_institution'),
                grad_date: getVal('sm_pers_graddate'),
                gpa: getVal('sm_pers_gpa'),
                license_no: getVal('sm_lic_no'),
                license_name: getVal('sm_lic_name'),
                license_issue: getVal('sm_lic_issue'),
                license_expire: getVal('sm_lic_expire'),
                license_org: getVal('sm_lic_org')
            };

            const url = editingStaffId ? ('/api/moph-staff/' + editingStaffId) : '/api/moph-staff';
            const method = editingStaffId ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) throw new Error('Save failed');
                toggleStaffModal();
                await loadStaffFromStorage();
                await loadFromLocalStorage();
                
                if (payload.position_status === 'ไม่มีคนครอง') {
                    if (typeof logPositionMovement === 'function') {
                        logPositionMovement({
                            date: new Date().toISOString().split('T')[0],
                            posNo: payload.position_number || '-',
                            posName: payload.position_line || '-',
                            staffType: payload.staff_type || '-',
                            agencyName: payload.agency_name || '-',
                            district: payload.agency_district || '-',
                            movementType: 'เปลี่ยนสถานะเป็นตำแหน่งว่าง',
                            details: 'ปรับเปลี่ยนสถานะตำแหน่งเป็นไม่มีคนครองจากระบบบริหารบุคลากร',
                            orderNo: '-'
                        });
                    }
                    alert('บันทึกเรียบร้อย: เนื่องจากสถานะตำแหน่งเป็น "ไม่มีคนครอง" ข้อมูลจะถูกย้ายไปแสดงที่แท็บ "การบริหารตำแหน่งว่าง" และบันทึกลงใน "ความเคลื่อนไหวตำแหน่ง"');
                }
            } catch (err) {
                alert('เกิดข้อผิดพลาดในการบันทึกลงฐานข้อมูล: ' + err.message);
            }
        }

        async function deleteStaffById(id) {
            if (!confirm('คุณแน่ใจว่าต้องการลบรายการนี้ออกจากฐานข้อมูล?')) return;
            try {
                const res = await fetch('/api/moph-staff/' + id, { method: 'DELETE' });
                if (!res.ok) throw new Error('Delete failed');
                await loadStaffFromStorage();
                await loadFromLocalStorage();
            } catch (err) {
                alert('เกิดข้อผิดพลาดในการลบ: ' + err.message);
            }
        }

        async function importStaffExcel(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            
            if (typeof XLSX === 'undefined') {
                alert('กำลังโหลดระบบอ่านไฟล์ Excel กรุณารอสักครู่แล้วลองใหม่ครับ');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                    
                    let dataRows = json.slice(2);
                    let validRows = dataRows.filter(row => row && row.length > 0 && (row[1] || row[2]));
                    
                    if (validRows.length === 0) {
                        alert('ไม่พบข้อมูลในไฟล์ Excel หรือรูปแบบไม่ถูกต้อง');
                        input.value = '';
                        return;
                    }
                    
                    const type = window.currentStaffType || 'พนักงานกระทรวงสาธารณสุข';
                    if (!confirm('พบข้อมูลจำนวน ' + validRows.length + ' รายการ ต้องการนำเข้าสู่ฐานข้อมูล ' + type + ' หรือไม่?')) {
                        input.value = '';
                        return;
                    }

                    const safeGet = (row, index) => (row[index] !== undefined && row[index] !== null) ? String(row[index]).trim() : '';

                    const payloads = validRows.map(row => ({
                        agency_prefix: safeGet(row, 1),
                        agency_district: safeGet(row, 2),
                        agency_sub_district: safeGet(row, 3),
                        agency_group: safeGet(row, 4),
                        agency_work: safeGet(row, 5),
                        agency_match: safeGet(row, 6),
                        position_number: safeGet(row, 7),
                        position_level: safeGet(row, 8),
                        position_line: safeGet(row, 9),
                        position_status: safeGet(row, 10),
                        staff_type: type, // Force selected staff type
                        personal_prefix: safeGet(row, 12),
                        personal_fname: safeGet(row, 13),
                        personal_lname: safeGet(row, 14),
                        personal_id_card: safeGet(row, 15),
                        hire_date: safeGet(row, 16),
                        hire_qual: safeGet(row, 17),
                        grad_date: safeGet(row, 18),
                        gpa: safeGet(row, 19),
                        license_name: safeGet(row, 20),
                        license_no: safeGet(row, 21),
                        license_issue: safeGet(row, 22),
                        license_expire: safeGet(row, 23)
                    }));

                    const res = await fetch('/api/moph-staff', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payloads)
                    });

                    if (!res.ok) throw new Error('API Bulk Insert Failed');
                    
                    alert('นำเข้าข้อมูลสู่ฐานข้อมูลส่วนกลางสำเร็จจำนวน ' + payloads.length + ' รายการ!');
                    loadStaffFromStorage();
                    
                } catch(error) {
                    console.error(error);
                    alert('เกิดข้อผิดพลาดในการนำเข้าสู่ฐานข้อมูล: ' + error.message);
                }
                input.value = '';
            };
            reader.readAsArrayBuffer(file);
        }

        
        function formatDateToThai(dateStr) {
            if (!dateStr) return '-';
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            const year = parseInt(parts[0]) + 543;
            const monthNames = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
            const month = monthNames[parseInt(parts[1]) - 1];
            const day = parseInt(parts[2]);
            return `${day} ${month} ${year}`;
        }

        function openAddModal() {
            editingRow = null;
            const form = document.querySelector('#dataModal form');
            if(form) form.reset();
            handleModalPrefixChange();
            
            if(document.getElementById('chk_ssj')) document.getElementById('chk_ssj').checked = false;
            if(document.getElementById('chk_chro')) document.getElementById('chk_chro').checked = false;
            if(document.getElementById('chk_akp')) document.getElementById('chk_akp').checked = false;
            
            toggleApprovalSection('ssj');
            toggleApprovalSection('chro');
            toggleApprovalSection('akp');
            
            toggleModal();
        }

        
        function formatOperationStatus(status, result) {
            if (!status || status === '-') return '-';
            let resultHtml = '';
            if (result === 'ผ่าน') {
                resultHtml = '<div class="text-xs text-green-600 font-medium mt-1"><i class="fas fa-check-circle"></i> ผ่าน</div>';
            } else if (result === 'ไม่ผ่าน') {
                resultHtml = '<div class="text-xs text-red-600 font-medium mt-1"><i class="fas fa-times-circle"></i> ไม่ผ่าน</div>';
            } else {
                resultHtml = '<div class="text-xs text-yellow-600 font-medium mt-1"><i class="fas fa-clock"></i> รอพิจารณา</div>';
            }
            return `<div class="leading-tight">${status}${resultHtml}</div>`;
        } 


        
        function restoreFileInputUI(input, fileUrl, fileName) {
            const labelSpan = input.closest('label').querySelector('.file-label-text');
            const downloadBtn = input.closest('.flex').querySelector('.btn-download-file');
            if (fileName && fileUrl) {
                labelSpan.innerHTML = '<i class="fas fa-file-pdf mr-1 text-red-500"></i> ' + fileName;
                input.setAttribute('data-fileurl', fileUrl);
                input.setAttribute('data-filename', fileName);
                downloadBtn.classList.remove('text-gray-600');
                downloadBtn.classList.add('text-green-600');
            } else {
                labelSpan.innerHTML = '<i class="fas fa-upload mr-1 text-gray-400"></i> อัพโหลด';
                input.removeAttribute('data-fileurl');
                input.removeAttribute('data-filename');
                downloadBtn.classList.add('text-gray-600');
                downloadBtn.classList.remove('text-green-600');
            }
        }

        function editVacantPosition(btn) {
            const tr = btn.closest('tr');
            editingRow = tr;
            const tds = tr.querySelectorAll('td');
            
            const prefix = tds[1].innerText.trim();
            const district = tds[2].innerText.trim();
            const rpst = tds[3].innerText.trim();
            const positionNo = tds[4].innerText.trim();
            const positionName = tds[5].innerText.trim();
            const empType = tds[6].innerText.trim();
            const formattedDate = tds[7].innerText.trim();
            const vacantDays = tds[8].innerText.trim();
            const opStatusVal = tr.getAttribute('data-opstatus') || '';
            const opResultVal = tr.getAttribute('data-opresult') || '-';
            const chro = tds[10].innerHTML.includes('fa-check');
              const akp = tds[11].innerHTML.includes('fa-check');
              const ssj = tds[12].innerHTML.includes('fa-check');

              const sectSsj = document.getElementById('section_ssj');
              sectSsj.querySelector('input[type="text"]').value = tr.getAttribute('data-ssj-doc') || '';
              sectSsj.querySelector('input[type="date"]').value = tr.getAttribute('data-ssj-date') || '';
              restoreFileInputUI(sectSsj.querySelector('input[type="file"]'), tr.getAttribute('data-ssj-fileurl'), tr.getAttribute('data-ssj-filename'));

              const sectChro = document.getElementById('section_chro');
              sectChro.querySelector('input[type="text"]').value = tr.getAttribute('data-chro-doc') || '';
              sectChro.querySelector('input[type="date"]').value = tr.getAttribute('data-chro-date') || '';
              restoreFileInputUI(sectChro.querySelector('input[type="file"]'), tr.getAttribute('data-chro-fileurl'), tr.getAttribute('data-chro-filename'));

              const sectAkp = document.getElementById('section_akp');
              sectAkp.querySelector('input[type="text"]').value = tr.getAttribute('data-akp-doc') || '';
              sectAkp.querySelector('input[type="date"]').value = tr.getAttribute('data-akp-date') || '';
              restoreFileInputUI(sectAkp.querySelector('input[type="file"]'), tr.getAttribute('data-akp-fileurl'), tr.getAttribute('data-akp-filename'));
                
                if(document.getElementById('chk_ssj')) document.getElementById('chk_ssj').checked = ssj;
                if(document.getElementById('chk_chro')) document.getElementById('chk_chro').checked = chro;
                if(document.getElementById('chk_akp')) document.getElementById('chk_akp').checked = akp;
                
                toggleApprovalSection('ssj');
                toggleApprovalSection('chro');
                toggleApprovalSection('akp');


            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if(el) {
                    if (val === '-' || !val) el.value = '';
                    else el.value = val;
                }
            };

            setVal('modal_prefix', prefix);
            setVal('modal_district', district);
            setVal('modal_rpst', rpst);
            setVal('modal_position_no', positionNo);
            setVal('modal_position_name', positionName);
            setVal('modal_emp_type', empType);
            handleEmpTypeChange();
            setVal('modal_operation_status', opStatusVal);
            setVal('modal_operation_result', opResultVal);

            if (formattedDate && formattedDate !== '-') {
                const thaiMonths = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
                const parts = formattedDate.split(' ');
                if(parts.length === 3) {
                    const d = parts[0].padStart(2, '0');
                    const m = String(thaiMonths.indexOf(parts[1]) + 1).padStart(2, '0');
                    const y = String(parseInt(parts[2]) - 543);
                    document.getElementById('modal_vacant_date').value = y + '-' + m + '-' + d;
                }
            } else {
                document.getElementById('modal_vacant_date').value = '';
            }

            const vDaysEl = document.getElementById('modal_vacant_days_display');
            if (vDaysEl) {
                vDaysEl.value = (vacantDays === '-' ? '' : vacantDays);
            }

            if(document.getElementById('chk_chro')) document.getElementById('chk_chro').checked = chro;
            if(document.getElementById('chk_akp')) document.getElementById('chk_akp').checked = akp;
            if(document.getElementById('chk_ssj')) document.getElementById('chk_ssj').checked = ssj;

            handleModalPrefixChange();
            toggleApprovalSection('ssj');
            toggleApprovalSection('chro');
            toggleApprovalSection('akp');

            toggleModal();
        }
        function toggleApprovalSection(id) {
            const chk = document.getElementById('chk_' + id);
            const section = document.getElementById('section_' + id);
            if (chk.checked) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

        function calculateVacantDays() {
            const dateInput = document.getElementById('modal_vacant_date').value;
            const displayInput = document.getElementById('modal_vacant_days_display');
            
            if (!dateInput) {
                displayInput.value = "";
                return;
            }

            const vacantDate = new Date(dateInput);
            const today = new Date();
            
            vacantDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);

            if (vacantDate > today) {
                displayInput.value = "ล่วงหน้า (ยังไม่ว่าง)";
                return;
            }

            const diffTime = Math.abs(today - vacantDate);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) {
                displayInput.value = "0 วัน";
            } else {
                const months = Math.floor(diffDays / 30);
                const days = diffDays % 30;
                
                let result = [];
                if (months > 0) result.push(months + " เดือน");
                if (days > 0) result.push(days + " วัน");
                
                displayInput.value = result.join(" ");
            }
        }

        function handleModalPrefixChange() {
            const prefix = document.getElementById('modal_prefix').value;
            const rpst = document.getElementById('modal_rpst');
            if (prefix === 'รพ.สต.') {
                rpst.disabled = false;
                rpst.required = true;
                rpst.focus();
            } else {
                rpst.disabled = true;
                rpst.required = false;
                rpst.value = "";
            }
        }

        function handleAgencyChange() {
            const agency = document.getElementById('reg-agency').value;
            const district = document.getElementById('reg-district');
            
            district.disabled = false;
            
            if (agency === 'สสจ.ศรีสะเกษ') {
                district.value = 'เมืองศรีสะเกษ';
                district.disabled = true;
            } else if (agency === 'รพท.') {
                district.value = 'อุทุมพรพิสัย';
                district.disabled = true;
            } else {
                district.value = "";
            }
        }

        // === App Tabs (Sidebar Menus) ===
        
window.currentStaffType = 'พนักงานกระทรวงสาธารณสุข';

function switchAppTab(tabId, element, staffType = null) {
    document.querySelectorAll('.app-tab-content').forEach(el => el.classList.remove('active'));
    
    document.querySelectorAll('.sidebar-item').forEach(el => {
        el.classList.remove('active', 'text-white');
        el.classList.add('text-green-50');
    });
    
    document.getElementById(tabId).classList.add('active');
    
    element.classList.add('active', 'text-white');
    element.classList.remove('text-green-50');

    const titleMap = {
        'tab-dashboard': 'แผงควบคุมแสดงสถานะอัตรากำลังคน',
        'tab-vacant': 'การบริหารตำแหน่งว่าง',
        'tab-staff-moph': 'พนักงานกระทรวงสาธารณสุข',
        'tab-movement': 'ความเคลื่อนไหวตำแหน่ง'
    };
    
    if (staffType) {
        window.currentStaffType = staffType;
        document.getElementById('page-title').innerText = staffType;
        
        // Update table header title
        const tableTitle = document.querySelector('#tab-staff-moph h3.font-bold');
        if(tableTitle) tableTitle.innerText = 'รายชื่อ' + staffType + 'ทั้งหมด';
        
        // Update modal title
        const modalTitle = document.querySelector('#staffModal h3.text-xl');
        if(modalTitle) modalTitle.innerHTML = '<i class="fas fa-user-md text-moph mr-2"></i>บันทึกข้อมูล' + staffType;
        const staffTypeInput = document.getElementById('sm_staff_type');
        if(staffTypeInput) staffTypeInput.value = staffType;
        
        // Refresh data
        if (typeof loadStaffFromStorage === 'function') loadStaffFromStorage();
    } else if(titleMap[tabId]) {
        document.getElementById('page-title').innerText = titleMap[tabId];
        if (tabId === 'tab-vacant' && typeof loadFromLocalStorage === 'function') {
            loadFromLocalStorage();
        } else if (tabId === 'tab-movement' && typeof loadMovementHistory === 'function') {
            loadMovementHistory();
        }
    }
}


        // === Position Movement Logic ===
        const defaultMovements = [
            {
                date: "2025-08-15",
                posNo: "685412",
                posName: "พยาบาลวิชาชีพ",
                staffType: "พนักงานกระทรวงสาธารณสุข",
                agencyName: "รพท.เมืองศรีสะเกษ",
                district: "เมืองศรีสะเกษ",
                movementType: "เปลี่ยนสถานะเป็นตำแหน่งว่าง",
                details: "ลาออกจากราชการ มีผล 15 ส.ค. 2568",
                orderNo: "คำสั่ง สสจ.ศก 142/2568"
            },
            {
                date: "2026-06-29",
                posNo: "11",
                posName: "นักวิชาการสาธารณสุข",
                staffType: "พนักงานกระทรวงสาธารณสุข",
                agencyName: "สสจ. (กลุ่มงาน บค.)",
                district: "เมืองศรีสะเกษ",
                movementType: "บรรจุใหม่ / ทำสัญญาจ้าง",
                details: "ผ่านการคัดเลือกสรรหาพนักงานกระทรวงสาธารณสุขทั่วไป",
                orderNo: "สัญญาจ้างเลขที่ 45/2569"
            },
            {
                date: "2026-07-15",
                posNo: "998877",
                posName: "เภสัชกร",
                staffType: "ลูกจ้างชั่วคราว (รายเดือน)",
                agencyName: "รพช.ราษีไศล",
                district: "ราษีไศล",
                movementType: "รับโอน / ย้าย",
                details: "ย้ายช่วยราชการตามความประสงค์ของหน่วยงาน",
                orderNo: "คำสั่ง สสจ.ศก 89/2569"
            },
            {
                date: "2026-09-01",
                posNo: "112233",
                posName: "นักวิชาการสาธารณสุข",
                staffType: "พนักงานกระทรวงสาธารณสุข",
                agencyName: "รพ.สต.บ้านตาอุด",
                district: "ขุขันธ์",
                movementType: "ขอเปลี่ยนตำแหน่ง/หน่วยงาน",
                details: "หน่วยงานขอสรรหาทดแทน / ขอปรับเกลี่ยตำแหน่ง",
                orderNo: "หนังสือ สสจ.ศก 0032/512"
            }
        ];

        function getMovementData() {
            let data = localStorage.getItem("position_movements");
            if (!data) {
                localStorage.setItem("position_movements", JSON.stringify(defaultMovements));
                return defaultMovements;
            }
            try {
                return JSON.parse(data);
            } catch(e) {
                return defaultMovements;
            }
        }

        function setMovementData(data) {
            localStorage.setItem("position_movements", JSON.stringify(data));
        }

        function toggleMovementModal() {
            const modal = document.getElementById("movementModal");
            if (modal.classList.contains("hidden")) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                const today = new Date().toISOString().split("T")[0];
                const dateInp = document.getElementById("mv_date");
                if (dateInp && !dateInp.value) dateInp.value = today;
            } else {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        }

        function openAddMovementModal() {
            const form = document.getElementById("movementForm");
            if (form) form.reset();
            toggleMovementModal();
        }

        function getMovementBadge(type) {
            switch(type) {
                case "เปลี่ยนสถานะเป็นตำแหน่งว่าง":
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200"><i class="fas fa-user-slash mr-1"></i>${type}</span>`;
                case "บรรจุใหม่ / ทำสัญญาจ้าง":
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200"><i class="fas fa-user-plus mr-1"></i>${type}</span>`;
                case "รับโอน / ย้าย":
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200"><i class="fas fa-random mr-1"></i>${type}</span>`;
                case "ขอเปลี่ยนตำแหน่ง/หน่วยงาน":
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200"><i class="fas fa-exchange-alt mr-1"></i>${type}</span>`;
                case "ลาออก / สิ้นสุดสัญญา":
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200"><i class="fas fa-sign-out-alt mr-1"></i>${type}</span>`;
                default:
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200"><i class="fas fa-arrow-up mr-1"></i>${type}</span>`;
            }
        }

        function loadMovementHistory() {
            const tbody = document.getElementById("movement-table-body");
            if (!tbody) return;
            const list = getMovementData();

            // Update stats
            const totalEl = document.getElementById("stat-movement-total");
            const vacantEl = document.getElementById("stat-movement-vacant");
            const newhireEl = document.getElementById("stat-movement-newhire");
            const transferEl = document.getElementById("stat-movement-transfer");

            if (totalEl) totalEl.innerText = list.length;
            if (vacantEl) vacantEl.innerText = list.filter(m => m.movementType === "เปลี่ยนสถานะเป็นตำแหน่งว่าง" || m.movementType === "ลาออก / สิ้นสุดสัญญา").length;
            if (newhireEl) newhireEl.innerText = list.filter(m => m.movementType === "บรรจุใหม่ / ทำสัญญาจ้าง" || m.movementType === "รับโอน / ย้าย").length;
            if (transferEl) transferEl.innerText = list.filter(m => m.movementType === "ขอเปลี่ยนตำแหน่ง/หน่วยงาน" || m.movementType === "เลื่อนระดับ").length;

            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="text-center py-10 text-gray-400 font-medium"><i class="fas fa-history text-3xl mb-2 text-gray-300 block"></i>ยังไม่มีประวัติความเคลื่อนไหวตำแหน่งในระบบ</td></tr>`;
                return;
            }

            tbody.innerHTML = "";
            list.forEach((item, index) => {
                const tr = document.createElement("tr");
                tr.className = "hover:bg-gray-50 transition";
                tr.innerHTML = `
                    <td class="text-center font-medium text-gray-500">${index + 1}</td>
                    <td class="text-center">${item.date ? formatDateToThai(item.date) : "-"}</td>
                    <td class="text-center font-semibold text-moph">${item.posNo || "-"}</td>
                    <td>${item.posName || "-"}</td>
                    <td><span class="inline-block px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700 font-medium">${item.staffType || "-"}</span></td>
                    <td>${item.agencyName || "-"}</td>
                    <td>${item.district || "-"}</td>
                    <td class="text-center">${getMovementBadge(item.movementType)}</td>
                    <td class="max-w-xs truncate" title="${item.details || ""}">${item.details || "-"}</td>
                    <td>${item.orderNo || "-"}</td>
                    <td class="text-center">
                        <button type="button" class="text-red-500 hover:text-red-700 mx-1" title="ลบรายการ" onclick="deleteMovement(${index})"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function saveMovement() {
            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value.trim() : "";
            const newMovement = {
                date: getVal("mv_date"),
                posNo: getVal("mv_pos_no"),
                posName: getVal("mv_pos_name"),
                staffType: getVal("mv_staff_type"),
                agencyName: getVal("mv_agency_name") || "-",
                district: getVal("mv_agency_district") || "-",
                movementType: getVal("mv_movement_type"),
                details: getVal("mv_details") || "-",
                orderNo: getVal("mv_order_no") || "-"
            };

            const list = getMovementData();
            list.unshift(newMovement);
            setMovementData(list);
            toggleMovementModal();
            loadMovementHistory();
            alert("บันทึกข้อมูลความเคลื่อนไหวตำแหน่งเรียบร้อยแล้ว");
        }

        function deleteMovement(index) {
            if (!confirm("คุณแน่ใจว่าต้องการลบประวัติความเคลื่อนไหวนี้?")) return;
            const list = getMovementData();
            list.splice(index, 1);
            setMovementData(list);
            loadMovementHistory();
        }

        function filterMovementTable() {
            const search = (document.getElementById("movement-search") ? document.getElementById("movement-search").value : "").toLowerCase();
            const filterType = document.getElementById("movement-type-filter") ? document.getElementById("movement-type-filter").value : "";
            const tbody = document.getElementById("movement-table-body");
            if (!tbody) return;
            const rows = tbody.querySelectorAll("tr");
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                const matchSearch = !search || text.includes(search);
                const matchType = !filterType || text.includes(filterType.toLowerCase());
                if (matchSearch && matchType) {
                    r.style.display = "";
                } else {
                    r.style.display = "none";
                }
            });
        }

        function exportMovementExcel() {
            if (typeof XLSX === "undefined") {
                alert("กำลังโหลดโมดูล Excel กรุณารอสักครู่");
                return;
            }
            const table = document.querySelector("#tab-movement table");
            if (!table) return;
            const wb = XLSX.utils.table_to_book(table, { sheet: "ความเคลื่อนไหวตำแหน่ง" });
            XLSX.writeFile(wb, "position_movements_" + new Date().toISOString().split("T")[0] + ".xlsx");
        }

        function logPositionMovement(movement) {
            const list = getMovementData();
            list.unshift(movement);
            setMovementData(list);
        }

        // === Modal Form ===
        function toggleModal() {
            const modal = document.getElementById('dataModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>

          <!-- Staff Modal (Add/Edit) -->
          <div id="staffModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
              <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col mx-4 overflow-hidden">
                  <div class="flex justify-between items-center p-5 border-b border-gray-200">
                      <h3 class="text-xl font-bold text-gray-800"><i class="fas fa-user-md text-moph mr-2"></i>บันทึกข้อมูลพนักงานกระทรวงสาธารณสุข</h3>
                      <button onclick="toggleStaffModal()" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                  </div>
                  <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-gray-50">
                      
                      <!-- Section 1: ข้อมูลตำแหน่งและสังกัดหน่วยงาน จ.18 -->
                      <h4 class="font-bold text-moph mb-3 flex items-center border-b pb-2"><i class="fas fa-building mr-2"></i> 1. ข้อมูลตำแหน่งและสังกัด (จ.18)</h4>
                      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งเลขที่</label><input type="text" id="sm_pos_no" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div>
                              <label class="block text-sm font-medium text-gray-700 mb-1">สถานะตำแหน่ง</label>
                              <select id="sm_pos_status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph">
                                  <option value="">-เลือก-</option>
                                  <option value="มีคนครอง">มีคนครอง</option>
                                  <option value="ไม่มีคนครอง">ไม่มีคนครอง</option>
                              </select>
                          </div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">อำเภอ จ.18</label><input type="text" id="sm_agency_district" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">คำนำหน้าหน่วยงาน จ.18</label><input type="text" id="sm_agency_prefix" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน จ.18</label><input type="text" id="sm_agency_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">กลุ่มงาน จ.18</label><input type="text" id="sm_agency_group" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงานภายใน จ.18</label><input type="text" id="sm_agency_internal" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งสายงาน</label><input type="text" id="sm_pos_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ประเภทตำแหน่ง</label><input type="text" id="sm_pos_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ระดับ</label><input type="text" id="sm_pos_level" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">ประเภทเจ้าหน้าที่</label><input type="text" id="sm_staff_type" value="พนักงานกระทรวงสาธารณสุข" readonly class="w-full border border-gray-300 bg-gray-100 rounded-md px-3 py-2 text-sm focus:outline-none"></div>
                      </div>

                      <!-- Section 1.1: สังกัดหน่วยงานที่ปฏิบัติงานจริง -->
                      <h4 class="font-bold text-moph mb-3 flex items-center border-b pb-2"><i class="fas fa-map-marker-alt mr-2"></i> สังกัดหน่วยงาน (ปฏิบัติงานจริง)</h4>
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">อำเภอ (จริง)</label><input type="text" id="sm_actual_district" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">คำนำหน้าหน่วยงาน (จริง)</label><input type="text" id="sm_actual_prefix" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน (จริง)</label><input type="text" id="sm_actual_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                      </div>

                      <!-- Section 2: ข้อมูลบุคคลและการปฏิบัติงาน -->
                      <h4 class="font-bold text-moph mb-3 flex items-center border-b pb-2"><i class="fas fa-user mr-2"></i> 2. ข้อมูลบุคคลและการปฏิบัติงาน</h4>
                      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                          <div>
                              <label class="block text-sm font-medium text-gray-700 mb-1">คำนำหน้า</label>
                              <select id="sm_pers_title" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph">
                                  <option value="นาย">นาย</option>
                                  <option value="นาง">นาง</option>
                                  <option value="นางสาว">นางสาว</option>
                              </select>
                          </div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ</label><input type="text" id="sm_pers_fname" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">นามสกุล</label><input type="text" id="sm_pers_lname" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">เลขประจำตัวประชาชน</label><input type="text" id="sm_pers_idcard" maxlength="13" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">สถานะการปฏิบัติราชการปัจจุบัน</label><input type="text" id="sm_duty_status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">วัน เดือน ปี ที่บรรจุ</label><input type="date" id="sm_pers_startdate" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                      </div>

                      <!-- Section 3: ข้อมูลวุฒิการศึกษา -->
                      <h4 class="font-bold text-moph mb-3 flex items-center border-b pb-2"><i class="fas fa-graduation-cap mr-2"></i> 3. วุฒิการศึกษาสูงสุด</h4>
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">วุฒิการศึกษาสูงสุด</label><input type="text" id="sm_pers_degree" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">สาขาวิชาเอกของวุฒิสูงสุด</label><input type="text" id="sm_major" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">สาขาวุฒิบัตรความเชี่ยวชาญของวุฒิสูงสุด</label><input type="text" id="sm_specialist_cert" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">สถาบันการศึกษาของวุฒิสูงสุด</label><input type="text" id="sm_institution" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">วันที่สำเร็จการศึกษาของวุฒิสูงสุด</label><input type="date" id="sm_pers_graddate" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">เกรดเฉลี่ย</label><input type="text" id="sm_pers_gpa" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                      </div>

                      <!-- Section 4: ใบประกอบวิชาชีพ -->
                      <h4 class="font-bold text-moph mb-3 flex items-center border-b pb-2"><i class="fas fa-id-card mr-2"></i> 4. ใบประกอบวิชาชีพ</h4>
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">เลขใบประกอบ</label><input type="text" id="sm_lic_no" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">ใบประกอบ (ชื่อใบประกอบ)</label><input type="text" id="sm_lic_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">วันออกใบประกอบ</label><input type="date" id="sm_lic_issue" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div><label class="block text-sm font-medium text-gray-700 mb-1">วันหมดอายุ</label><input type="date" id="sm_lic_expire" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                          <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">สถาบันออกใบประกอบ</label><input type="text" id="sm_lic_org" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph"></div>
                      </div>

                  </div>
                  <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                      <button onclick="toggleStaffModal()" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition shadow-sm font-medium">ยกเลิก</button>
                      <button onclick="saveStaffMoph()" class="px-5 py-2 bg-moph text-white rounded-md hover:bg-moph/90 transition shadow-sm font-medium">บันทึกข้อมูล</button>
                  </div>
              </div>
          </div>

<script>
        // Initial setup for staff categories
        document.addEventListener('DOMContentLoaded', () => {
            // One-time cleanup of contaminated old staff_moph_data
            if (!localStorage.getItem('staff_storage_v4_migrated')) {
                localStorage.removeItem('staff_moph_data');
                localStorage.removeItem('staff_dummy_init');
                localStorage.setItem('staff_storage_v4_migrated', 'true');
            }
        });
</script>
    <!-- Movement Modal (Add Movement) -->
    <div id="movementModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col mx-4 overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-exchange-alt text-moph"></i> <span>บันทึกความเคลื่อนไหวตำแหน่ง</span>
                </h3>
                <button onclick="toggleMovementModal()" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-gray-50">
                <form id="movementForm" onsubmit="event.preventDefault(); saveMovement();" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เกิดความเคลื่อนไหว <span class="text-red-500">*</span></label>
                            <input type="date" id="mv_date" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งเลขที่ <span class="text-red-500">*</span></label>
                            <input type="text" id="mv_pos_no" required placeholder="เช่น 685412" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งสายงาน <span class="text-red-500">*</span></label>
                            <input type="text" id="mv_pos_name" required placeholder="เช่น พยาบาลวิชาชีพ" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทเจ้าหน้าที่</label>
                            <select id="mv_staff_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                                <option value="พนักงานกระทรวงสาธารณสุข">พนักงานกระทรวงสาธารณสุข</option>
                                <option value="พนักงานราชการ">พนักงานราชการ</option>
                                <option value="ลูกจ้างประจำ">ลูกจ้างประจำ</option>
                                <option value="ลูกจ้างชั่วคราว (รายเดือน)">ลูกจ้างชั่วคราว (รายเดือน)</option>
                                <option value="ลูกจ้างชั่วคราวรายวัน/จ้างเหมา">ลูกจ้างชั่วคราวรายวัน/จ้างเหมา</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">สังกัดหน่วยงาน</label>
                            <input type="text" id="mv_agency_name" placeholder="เช่น รพ.สต.บ้านตาอุด" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">อำเภอ</label>
                            <input type="text" id="mv_agency_district" placeholder="เช่น ขุขันธ์" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทความเคลื่อนไหว <span class="text-red-500">*</span></label>
                            <select id="mv_movement_type" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                                <option value="เปลี่ยนสถานะเป็นตำแหน่งว่าง">เปลี่ยนสถานะเป็นตำแหน่งว่าง</option>
                                <option value="บรรจุใหม่ / ทำสัญญาจ้าง">บรรจุใหม่ / ทำสัญญาจ้าง</option>
                                <option value="รับโอน / ย้าย">รับโอน / ย้าย</option>
                                <option value="ขอเปลี่ยนตำแหน่ง/หน่วยงาน">ขอเปลี่ยนตำแหน่ง/หน่วยงาน</option>
                                <option value="ลาออก / สิ้นสุดสัญญา">ลาออก / สิ้นสุดสัญญา</option>
                                <option value="เลื่อนระดับ">เลื่อนระดับ</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด / หมายเหตุ</label>
                            <textarea id="mv_details" rows="2" placeholder="ระบุเหตุผล หรือรายละเอียดความเคลื่อนไหว..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">คำสั่ง / หนังสืออ้างอิง</label>
                            <input type="text" id="mv_order_no" placeholder="เช่น คำสั่ง สสจ.ศก 142/2568" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-moph focus:border-moph">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="toggleMovementModal()" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition shadow-sm font-medium text-sm">ยกเลิก</button>
                        <button type="submit" class="px-5 py-2 bg-moph text-white rounded-md hover:bg-moph/90 transition shadow-sm font-medium text-sm">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>