<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ระบบการจ้างทางเลือก - สสจ.ศรีสะเกษ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Sarabun', 'sans-serif'],
                    },
                    colors: {
                        moph: {
                            light: '#4ade80',
                            DEFAULT: '#16a34a',
                            dark: '#15803d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        .bg-gradient-moph {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
</head>
<body class="bg-gray-50 h-screen overflow-hidden">

    <!-- ======================= AUTH VIEW (CENTERED CARD) ======================= -->
    <div id="view-auth" class="view-section active h-full w-full relative flex items-center justify-center p-4" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%);">
        
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
                    <div class="w-16 h-16 mx-auto bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-3 border-2 border-white/40 shadow-lg">
                        <i class="fas fa-hospital-user text-3xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold mb-1 drop-shadow-md">ระบบการจ้างทางเลือก</h1>
                    <p class="text-green-100 text-sm font-light">สำนักงานสาธารณสุขจังหวัดศรีสะเกษ</p>
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
            <div class="p-6 text-center border-b border-white/20">
                <div class="w-20 h-20 mx-auto bg-white rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-hospital text-4xl text-moph-dark"></i>
                </div>
                <h1 class="text-xl font-bold leading-tight">ระบบการจ้างทางเลือก</h1>
                <p class="text-sm text-green-100 mt-1">สสจ.ศรีสะเกษ</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="#" onclick="switchAppTab('tab-dashboard', this)" class="sidebar-item active flex items-center px-6 py-3 text-white">
                            <i class="fas fa-chart-line w-6"></i>
                            <span>1. แดชบอร์ดอัตรากำลังคน</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="switchAppTab('tab-vacant', this)" class="sidebar-item flex items-center px-6 py-3 text-green-50">
                            <i class="fas fa-chair w-6"></i>
                            <span>2. การบริหารตำแหน่งว่าง</span>
                        </a>
                    </li>
                    <!-- Mock menus -->
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-tie w-6"></i><span>3. พนักงานราชการ</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user w-6"></i><span>4. ลูกจ้างประจำ</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-md w-6"></i><span>5. พนักงาน กสธ.</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-user-clock w-6"></i><span>6. ลูกจ้างชั่วคราว (รายเดือน)</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-users w-6"></i><span class="text-sm">7. ลูกจ้างชั่วคราวรายวัน/จ้างเหมา</span></a></li>
                    <li><a href="#" class="sidebar-item flex items-center px-6 py-3 text-green-50"><i class="fas fa-bell w-6 relative"><span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full h-3 w-3 flex items-center justify-center">3</span></i><span>8. แจ้งเตือนใบประกอบฯ</span></a></li>
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
                <h2 id="page-title" class="text-2xl font-bold text-gray-800">1. แดชบอร์ดแสดงสถานการณ์อัตรากำลังคน</h2>
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
                        <button onclick="toggleModal()" class="bg-moph hover:bg-moph-dark text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> เพิ่มข้อมูลตำแหน่งว่าง
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 table-container">
                        <table class="w-full complex-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="w-16">ลำดับ</th>
                                    <th rowspan="2">คำนำหน้า</th>
                                    <th rowspan="2">ประเภท<br>หน่วยงาน</th>
                                    <th rowspan="2">รพ.สต.</th>
                                    <th rowspan="2">ตำแหน่ง<br>เลขที่</th>
                                    <th rowspan="2">ตำแหน่ง<br>สายงาน</th>
                                    <th rowspan="2">ประเภทเจ้าหน้าที่</th>
                                    <th rowspan="2">วันที่ตำแหน่งว่าง</th>
                                    <th rowspan="2">เขต<br>อนุมัติ</th>
                                    <th rowspan="2">สป.<br>อนุมัติ</th>
                                    <th rowspan="2">สสจ.<br>อนุมัติ</th>
                                    <th rowspan="2">จำนวน<br>วันที่ว่าง</th>
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
                            <tbody>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="text-center">1</td>
                                    <td>นาย</td>
                                    <td>รพท.</td>
                                    <td>รพ.ศรีสะเกษ</td>
                                    <td class="text-center">685412</td>
                                    <td>พยาบาลวิชาชีพ</td>
                                    <td>พนักงาน กสธ.</td>
                                    <td class="text-center">15 ส.ค. 2568</td>
                                    <td class="text-center"><i class="fas fa-check text-green-500"></i></td>
                                    <td class="text-center"><i class="fas fa-clock text-yellow-500"></i></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">45</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">ว่างเดิม</td>
                                    <td class="text-center">1 ต.ค. 2568</td>
                                    <td class="text-center">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
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
                                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                                    <option value="" disabled selected>เลือกคำนำหน้า</option>
                                    <option>รพท.</option>
                                    <option>รพช.</option>
                                    <option>สสอ.</option>
                                    <option>รพ.สต.</option>
                                    <option>สสจ.</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทหน่วยงาน</label>
                                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                                    <option>สสอ.</option>
                                    <option>รพท.</option>
                                    <option>รพช.</option>
                                    <option>รพ.สต.</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">รพ.สต.</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งเลขที่</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่งสายงาน</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทเจ้าหน้าที่</label>
                                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                                    <option>พนักงานราชการ</option>
                                    <option>ลูกจ้างประจำ</option>
                                    <option>พนักงานกระทรวงสาธารณสุข</option>
                                    <option>ลูกจ้างชั่วคราว</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ตำแหน่งว่าง</label>
                                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนวันที่ว่าง (วัน)</label>
                                <input type="number" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-moph">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h4 class="text-moph font-semibold border-b pb-2 mb-4"><i class="fas fa-check-circle mr-2"></i> สถานะการอนุมัติ</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-moph focus:ring-moph w-4 h-4">
                                <span class="text-sm text-gray-700">เขต อนุมัติแล้ว</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-moph focus:ring-moph w-4 h-4">
                                <span class="text-sm text-gray-700">สป. อนุมัติแล้ว</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-moph focus:ring-moph w-4 h-4">
                                <span class="text-sm text-gray-700">สสจ. อนุมัติแล้ว</span>
                            </label>
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
                <button onclick="toggleModal()" class="px-4 py-2 bg-moph text-white rounded-md hover:bg-moph-dark transition shadow">บันทึกข้อมูล</button>
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
                alert('Server error');
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
        function switchAppTab(tabId, element) {
            document.querySelectorAll('.app-tab-content').forEach(el => el.classList.remove('active'));
            
            document.querySelectorAll('.sidebar-item').forEach(el => {
                el.classList.remove('active', 'text-white');
                el.classList.add('text-green-50');
            });
            
            document.getElementById(tabId).classList.add('active');
            
            element.classList.add('active', 'text-white');
            element.classList.remove('text-green-50');

            const titleMap = {
                'tab-dashboard': '1. แดชบอร์ดแสดงสถานการณ์อัตรากำลังคน',
                'tab-vacant': '2. การบริหารตำแหน่งว่าง'
            };
            if(titleMap[tabId]) {
                document.getElementById('page-title').innerText = titleMap[tabId];
            }
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
</body>
</html>
