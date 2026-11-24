import React, { useState } from 'react';
import { UserRole, NavTab } from '../types';
import {
  Radio,
  ShieldCheck,
  Zap,
  Lock,
  Eye,
  EyeOff,
  Clock,
  LogIn,
  Car,
  Bike,
  Truck,
  RotateCw,
  PhoneCall,
  CheckCircle2,
  AlertCircle,
  HelpCircle,
  BadgeAlert,
  CreditCard,
  User,
  Activity,
  Layers,
  Sparkles,
} from 'lucide-react';

interface OverviewViewProps {
  onLoginRole: (role: UserRole, targetTab: NavTab) => void;
  onShowToast: (message: string, type?: 'success' | 'info' | 'error') => void;
}

/** Menampilkan halaman ringkasan dengan formulir simulasi login berbasis peran. */
export const OverviewView: React.FC<OverviewViewProps> = ({
  onLoginRole,
  onShowToast,
}) => {
  const [selectedRole, setSelectedRole] = useState<UserRole>('petugas');
  const [userId, setUserId] = useState('');
  const [password, setPassword] = useState('');
  const [shift, setShift] = useState('1');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  // Menentukan label, kredensial demo, dan tujuan navigasi untuk tiap peran.
  const roleMeta = {
    petugas: {
      title: 'Masuk Sistem Petugas',
      badgeTitle: 'Petugas Gerbang & POS',
      desc: 'Entri pelat nomor, validasi barcode karcis, transaksi tunai/QRIS, dan buka barrier gate.',
      defaultUser: 'PTG-10492',
      defaultPass: 'smartparkir2025',
      targetTab: 'pos-petugas' as NavTab,
      hasShift: true,
    },
    admin: {
      title: 'Masuk Konsol Administrator',
      badgeTitle: 'Administrator Sistem',
      desc: 'Manajemen tarif dinamis, penomoran slot, audit log petugas, dan konfigurasi master IoT gate.',
      defaultUser: 'ADM-SYS99',
      defaultPass: 'adminpass2025',
      targetTab: 'admin-master' as NavTab,
      hasShift: false,
    },
    owner: {
      title: 'Masuk Dashboard Owner',
      badgeTitle: 'Direksi & Pemilik Aset',
      desc: 'Monitoring real-time pendapatan harian/bulanan, tren okupansi, dan proyeksi omzet.',
      defaultUser: 'OWNER-DIR',
      defaultPass: 'ownerpass2025',
      targetTab: 'owner-rekap' as NavTab,
      hasShift: false,
    },
  };

  // Mengisi formulir dengan kredensial demo dari peran yang sedang dipilih.
  const handleDemoFill = () => {
    const meta = roleMeta[selectedRole];
    setUserId(meta.defaultUser);
    setPassword(meta.defaultPass);
    onShowToast(`Kredensial demo terisi: ${meta.defaultUser}`, 'info');
  };

  // Memvalidasi simulasi login lalu membuka tampilan peran yang dipilih.
  const handleAuthSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const effectiveUserId = userId.trim() || roleMeta[selectedRole].defaultUser;
    setIsLoading(true);

    setTimeout(() => {
      setIsLoading(false);
      onShowToast(
        `Login berhasil sebagai ${selectedRole.toUpperCase()} (${effectiveUserId})`,
        'success'
      );
      onLoginRole(selectedRole, roleMeta[selectedRole].targetTab);
    }, 800);
  };

  return (
    <div className="flex flex-col w-full space-y-4 pb-2">
      {/* Live Operational Status Ticker */}
      <div className="bg-[#dce9ff] rounded-2xl p-3 shadow-sm flex items-center justify-between text-[#0b1c30] border border-[#d3e4fe]">
        <div className="flex items-center gap-2">
          <span className="relative flex h-2.5 w-2.5">
            <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4edea3] opacity-75"></span>
            <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#009c6b]"></span>
          </span>
          <span className="font-['JetBrains_Mono'] text-[12px] font-bold tracking-wide uppercase text-[#0b1c30]">
            Sistem Online
          </span>
        </div>
        <div className="flex items-center gap-1.5 bg-white px-2.5 py-1 rounded-full text-[#0051d5] font-['JetBrains_Mono'] text-[11px] shadow-xs border border-[#d3e4fe]">
          <Activity className="w-3.5 h-3.5" />
          <span className="font-semibold">3 Gerbang Aktif</span>
        </div>
      </div>

      {/* Hero Welcome Card */}
      <div className="bg-[#0f2042] text-white rounded-2xl p-4 shadow-md relative overflow-hidden">
        <div className="relative z-10 space-y-2">
          <div className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#316bf3] text-white font-['JetBrains_Mono'] text-[11px] font-semibold">
            <ShieldCheck className="w-3.5 h-3.5" />
            <span>Enterprise Gate Terminal v3.4</span>
          </div>
          <h2 className="font-['Space_Grotesk'] text-[22px] font-bold tracking-tight text-white leading-snug">
            Sistem Manajemen Parkir Cerdas, Cepat, &amp; Transparan
          </h2>
          <p className="font-['Hanken_Grotesk'] text-[13px] text-[#d3e4fe] font-normal leading-relaxed">
            Otomasi pencatatan kendaraan real-time, integrasi palang otomatis, dan rekapitulasi audit instan.
          </p>
        </div>
        {/* Ambient Graphic Accent */}
        <div className="absolute -right-6 -bottom-6 w-36 h-36 rounded-full bg-[#316bf3]/25 blur-2xl pointer-events-none"></div>
      </div>

      {/* Live Capacity Matrix (Slot Parkir) */}
      <div className="bg-white rounded-2xl p-4 shadow-sm space-y-3.5 border border-[#e5eeff]">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-7 h-7 rounded-lg bg-[#eff4ff] text-[#0051d5] flex items-center justify-center font-bold">
              P
            </div>
            <h3 className="font-['Space_Grotesk'] text-[17px] font-bold text-[#000922]">
              Kapasitas Slot Terkini
            </h3>
          </div>
          <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f] flex items-center gap-1">
            <RotateCw className="w-3 h-3 animate-spin text-[#0051d5]" />
            Live Sensor
          </span>
        </div>

        {/* Capacity Items Grid */}
        <div className="grid grid-cols-1 gap-2.5">
          {/* Mobil */}
          <div className="bg-[#eff4ff] p-3 rounded-xl flex flex-col gap-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2.5">
                <div className="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#000922] shadow-xs">
                  <Car className="w-4 h-4 text-[#0051d5]" />
                </div>
                <div>
                  <p className="font-['Hanken_Grotesk'] text-[14px] font-bold text-[#0b1c30]">
                    Slot Mobil
                  </p>
                  <p className="font-['Hanken_Grotesk'] text-[11px] text-[#45464e]">
                    Zona A1 - A4
                  </p>
                </div>
              </div>
              <div className="text-right">
                <span className="font-['JetBrains_Mono'] text-[14px] font-bold text-[#0051d5]">
                  42
                </span>
                <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                  {' '}/ 100 Tersedia
                </span>
              </div>
            </div>
            <div className="w-full bg-[#d3e4fe] rounded-full h-2 overflow-hidden">
              <div
                className="bg-[#0051d5] h-2 rounded-full transition-all duration-700"
                style={{ width: '58%' }}
              ></div>
            </div>
          </div>

          {/* Sepeda Motor */}
          <div className="bg-[#eff4ff] p-3 rounded-xl flex flex-col gap-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2.5">
                <div className="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#000922] shadow-xs">
                  <Bike className="w-4 h-4 text-[#009c6b]" />
                </div>
                <div>
                  <p className="font-['Hanken_Grotesk'] text-[14px] font-bold text-[#0b1c30]">
                    Slot Motor
                  </p>
                  <p className="font-['Hanken_Grotesk'] text-[11px] text-[#45464e]">
                    Area Khusus B1 - B8
                  </p>
                </div>
              </div>
              <div className="text-right">
                <span className="font-['JetBrains_Mono'] text-[14px] font-bold text-[#009c6b]">
                  185
                </span>
                <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                  {' '}/ 300 Tersedia
                </span>
              </div>
            </div>
            <div className="w-full bg-[#d3e4fe] rounded-full h-2 overflow-hidden">
              <div
                className="bg-[#009c6b] h-2 rounded-full transition-all duration-700"
                style={{ width: '38%' }}
              ></div>
            </div>
          </div>

          {/* Truk / Bus */}
          <div className="bg-[#eff4ff] p-3 rounded-xl flex flex-col gap-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2.5">
                <div className="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#000922] shadow-xs">
                  <Truck className="w-4 h-4 text-[#ba1a1a]" />
                </div>
                <div>
                  <p className="font-['Hanken_Grotesk'] text-[14px] font-bold text-[#0b1c30]">
                    Truk &amp; Bus
                  </p>
                  <p className="font-['Hanken_Grotesk'] text-[11px] text-[#45464e]">
                    Dermaga C
                  </p>
                </div>
              </div>
              <div className="text-right">
                <span className="font-['JetBrains_Mono'] text-[14px] font-bold text-[#ba1a1a]">
                  6
                </span>
                <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                  {' '}/ 15 Tersedia
                </span>
              </div>
            </div>
            <div className="w-full bg-[#d3e4fe] rounded-full h-2 overflow-hidden">
              <div
                className="bg-[#ba1a1a] h-2 rounded-full transition-all duration-700"
                style={{ width: '60%' }}
              ></div>
            </div>
          </div>
        </div>
      </div>

      {/* Role Access Portal & Unified Login */}
      <div className="bg-white rounded-2xl p-4 shadow-sm space-y-3.5 border border-[#e5eeff]">
        <div className="flex items-center justify-between">
          <div>
            <h3 className="font-['Space_Grotesk'] text-[17px] font-bold text-[#000922]">
              Akses Multi-Role
            </h3>
            <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
              Pilih tingkatan otorisasi akun Anda
            </p>
          </div>
          <button
            type="button"
            onClick={handleDemoFill}
            className="px-2.5 py-1 bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0051d5] rounded-xl font-['JetBrains_Mono'] text-[11px] font-bold flex items-center gap-1 active:scale-95 transition-all border border-[#d3e4fe]"
          >
            <Zap className="w-3.5 h-3.5" />
            <span>Demo Fill</span>
          </button>
        </div>

        {/* Multi-Role Tab Selectors */}
        <div className="grid grid-cols-3 gap-1.5 p-1 bg-[#dce9ff] rounded-xl">
          <button
            type="button"
            onClick={() => setSelectedRole('petugas')}
            className={`flex flex-col items-center py-2 px-1 rounded-lg transition-all ${
              selectedRole === 'petugas'
                ? 'bg-[#0f2042] text-white shadow-sm font-bold'
                : 'text-[#45464e] hover:text-[#0b1c30]'
            }`}
          >
            <CreditCard className="w-5 h-5 mb-0.5" />
            <span className="font-['JetBrains_Mono'] text-[11px]">Petugas</span>
          </button>

          <button
            type="button"
            onClick={() => setSelectedRole('admin')}
            className={`flex flex-col items-center py-2 px-1 rounded-lg transition-all ${
              selectedRole === 'admin'
                ? 'bg-[#0f2042] text-white shadow-sm font-bold'
                : 'text-[#45464e] hover:text-[#0b1c30]'
            }`}
          >
            <ShieldCheck className="w-5 h-5 mb-0.5" />
            <span className="font-['JetBrains_Mono'] text-[11px]">Admin</span>
          </button>

          <button
            type="button"
            onClick={() => setSelectedRole('owner')}
            className={`flex flex-col items-center py-2 px-1 rounded-lg transition-all ${
              selectedRole === 'owner'
                ? 'bg-[#0f2042] text-white shadow-sm font-bold'
                : 'text-[#45464e] hover:text-[#0b1c30]'
            }`}
          >
            <Layers className="w-5 h-5 mb-0.5" />
            <span className="font-['JetBrains_Mono'] text-[11px]">Owner</span>
          </button>
        </div>

        {/* Role Context Helper Banner */}
        <div className="bg-[#eff4ff] p-2.5 rounded-xl flex items-start gap-2 border border-[#d3e4fe]">
          <HelpCircle className="w-4 h-4 text-[#0051d5] shrink-0 mt-0.5" />
          <p className="font-['Hanken_Grotesk'] text-[12px] text-[#0b1c30] leading-snug">
            <span className="font-bold text-[#000922]">
              {roleMeta[selectedRole].badgeTitle}:
            </span>{' '}
            {roleMeta[selectedRole].desc}
          </p>
        </div>

        {/* Login Form */}
        <form onSubmit={handleAuthSubmit} className="space-y-3">
          {/* Input NIP / Username */}
          <div className="space-y-1">
            <label className="block font-['JetBrains_Mono'] text-[12px] font-semibold text-[#0b1c30]">
              ID Pengguna / NIP Petugas
            </label>
            <div className="relative flex items-center">
              <User className="w-4 h-4 absolute left-3 text-[#75777f]" />
              <input
                type="text"
                value={userId}
                onChange={(e) => setUserId(e.target.value)}
                placeholder={`Contoh: ${roleMeta[selectedRole].defaultUser}`}
                className="w-full pl-9 pr-3 py-2.5 bg-[#eff4ff] text-[#0b1c30] rounded-xl font-['JetBrains_Mono'] text-[13px] border border-[#d3e4fe] focus:outline-none focus:ring-2 focus:ring-[#0051d5]"
              />
            </div>
          </div>

          {/* Input Password */}
          <div className="space-y-1">
            <div className="flex items-center justify-between">
              <label className="block font-['JetBrains_Mono'] text-[12px] font-semibold text-[#0b1c30]">
                Kata Sandi
              </label>
              <button
                type="button"
                onClick={() => onShowToast('Reset password hubungi IT Administrator di Ext. 402', 'info')}
                className="font-['JetBrains_Mono'] text-[11px] text-[#0051d5] hover:underline"
              >
                Lupa sandi?
              </button>
            </div>
            <div className="relative flex items-center">
              <Lock className="w-4 h-4 absolute left-3 text-[#75777f]" />
              <input
                type={showPassword ? 'text' : 'password'}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full pl-9 pr-10 py-2.5 bg-[#eff4ff] text-[#0b1c30] rounded-xl font-['JetBrains_Mono'] text-[13px] border border-[#d3e4fe] focus:outline-none focus:ring-2 focus:ring-[#0051d5]"
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 text-[#75777f] hover:text-[#0b1c30]"
              >
                {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>

          {/* Shift Selection (Petugas Only) */}
          {roleMeta[selectedRole].hasShift && (
            <div className="space-y-1">
              <label className="block font-['JetBrains_Mono'] text-[12px] font-semibold text-[#0b1c30]">
                Sesi Operasional (Shift Kerja)
              </label>
              <div className="relative flex items-center">
                <Clock className="w-4 h-4 absolute left-3 text-[#75777f]" />
                <select
                  value={shift}
                  onChange={(e) => setShift(e.target.value)}
                  className="w-full pl-9 pr-8 py-2.5 bg-[#eff4ff] text-[#0b1c30] rounded-xl font-['Hanken_Grotesk'] text-[13px] border border-[#d3e4fe] appearance-none focus:outline-none focus:ring-2 focus:ring-[#0051d5]"
                >
                  <option value="1">Shift 1 Pagi (06:00 - 14:00 WIB)</option>
                  <option value="2">Shift 2 Sore (14:00 - 22:00 WIB)</option>
                  <option value="3">Shift 3 Malam (22:00 - 06:00 WIB)</option>
                </select>
              </div>
            </div>
          )}

          {/* Submit CTA Button */}
          <button
            type="submit"
            disabled={isLoading}
            className="w-full py-3 bg-[#0f2042] text-white hover:bg-[#000922] font-['Space_Grotesk'] text-[15px] font-bold rounded-xl shadow-md active:scale-[0.99] transition-all flex items-center justify-center gap-2 mt-2"
          >
            {isLoading ? (
              <>
                <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span>Memvalidasi Sesi...</span>
              </>
            ) : (
              <>
                <LogIn className="w-4 h-4" />
                <span>{roleMeta[selectedRole].title}</span>
              </>
            )}
          </button>
        </form>
      </div>

      {/* Public Parking Tariff Information */}
      <div className="bg-white rounded-2xl p-4 shadow-sm space-y-3 border border-[#e5eeff]">
        <div className="flex items-center gap-2">
          <div className="w-8 h-8 rounded-lg bg-[#eff4ff] text-[#0051d5] flex items-center justify-center">
            <CreditCard className="w-4 h-4" />
          </div>
          <div>
            <h3 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
              Informasi Tarif Parkir
            </h3>
            <p className="font-['Hanken_Grotesk'] text-[11px] text-[#45464e]">
              Sesuai Perda &amp; Regulasi Lokasi Aktif
            </p>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-2">
          <div className="bg-[#eff4ff] p-3 rounded-xl space-y-1 border border-[#d3e4fe]">
            <div className="flex items-center gap-1.5 text-[#45464e]">
              <Bike className="w-4 h-4 text-[#0051d5]" />
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#0b1c30]">Motor</span>
            </div>
            <p className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922] leading-tight">
              Rp 2.000
            </p>
            <p className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
              Per Jam Pertama
            </p>
          </div>

          <div className="bg-[#eff4ff] p-3 rounded-xl space-y-1 border border-[#d3e4fe]">
            <div className="flex items-center gap-1.5 text-[#45464e]">
              <Car className="w-4 h-4 text-[#0051d5]" />
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#0b1c30]">Mobil</span>
            </div>
            <p className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922] leading-tight">
              Rp 5.000
            </p>
            <p className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
              Per Jam Pertama
            </p>
          </div>
        </div>

        <div className="bg-[#eff4ff] p-2.5 rounded-xl flex items-center justify-between text-[#0b1c30] border border-[#d3e4fe]">
          <div className="flex items-center gap-2">
            <span className="w-2 h-2 rounded-full bg-[#009c6b]"></span>
            <span className="font-['Hanken_Grotesk'] text-[12px]">
              Mendukung Pembayaran QRIS &amp; Uang Elektronik
            </span>
          </div>
          <span className="font-['JetBrains_Mono'] text-[10px] text-[#009c6b] bg-white px-2 py-0.5 rounded-full font-bold border border-[#d3e4fe]">
            24 JAM
          </span>
        </div>
      </div>

      {/* IT Helpdesk Support Card */}
      <div className="bg-[#dce9ff] rounded-2xl p-4 shadow-sm flex items-center justify-between border border-[#d3e4fe]">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-[#0051d5] text-white flex items-center justify-center shadow-sm">
            <PhoneCall className="w-5 h-5" />
          </div>
          <div>
            <p className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
              Helpdesk Kendala POS
            </p>
            <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
              Palang macet / struk printer error
            </p>
          </div>
        </div>
        <button
          type="button"
          onClick={() => onShowToast('Menghubungi Tim Support POS (Ext. 402)...', 'info')}
          className="px-3.5 py-1.5 bg-white text-[#0051d5] rounded-xl font-['JetBrains_Mono'] text-[12px] font-bold shadow-xs active:bg-[#eff4ff] border border-[#d3e4fe]"
        >
          Hubungi
        </button>
      </div>
    </div>
  );
};
