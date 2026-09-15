import React, { useState } from 'react';
import { ASSETS } from '../data/mockData';
import {
  LogOut,
  Calendar,
  RotateCw,
  Download,
  FileText,
  FileSpreadsheet,
  TrendingUp,
  QrCode,
  Banknote,
  Car,
  Clock,
  Flame,
  ShieldCheck,
  MessageSquare,
  Mail,
  CheckCircle2,
  ChevronDown,
  Building2,
} from 'lucide-react';

interface OwnerRekapViewProps {
  onShowToast: (message: string, type?: 'success' | 'info' | 'error') => void;
  onLogout: () => void;
}

export const OwnerRekapView: React.FC<OwnerRekapViewProps> = ({
  onShowToast,
  onLogout,
}) => {
  const [selectedPeriod, setSelectedPeriod] = useState<
    'today' | 'yesterday' | 'this-week' | 'this-month' | 'custom'
  >('custom');

  const [startDate, setStartDate] = useState('01 Mei 2025');
  const [endDate, setEndDate] = useState('15 Mei 2025');
  const [isExportMenuOpen, setIsExportMenuOpen] = useState(false);
  const [activeHourlyBar, setActiveHourlyBar] = useState<string | null>(null);

  const periods = [
    { id: 'today' as const, label: 'Hari Ini' },
    { id: 'yesterday' as const, label: 'Kemarin' },
    { id: 'this-week' as const, label: 'Minggu Ini' },
    { id: 'this-month' as const, label: 'Bulan Ini' },
    { id: 'custom' as const, label: 'Kustom' },
  ];

  const hourlyData = [
    { time: '06:00', height: 28, value: '28%' },
    { time: '08:00', height: 62, value: '62%' },
    { time: '11:00', height: 94, value: '94%', peak: true },
    { time: '13:00', height: 100, value: 'MAX', max: true },
    { time: '15:00', height: 70, value: '70%' },
    { time: '17:00', height: 85, value: '85%' },
    { time: '19:00', height: 75, value: '75%' },
    { time: '21:00', height: 35, value: '35%' },
  ];

  const handlePeriodChange = (id: typeof selectedPeriod) => {
    setSelectedPeriod(id);
    if (id === 'today') {
      setStartDate('Hari ini');
      setEndDate('Hari ini');
    } else if (id === 'yesterday') {
      setStartDate('Kemarin');
      setEndDate('Kemarin');
    } else if (id === 'this-week') {
      setStartDate('Senin ini');
      setEndDate('Hari ini');
    } else if (id === 'this-month') {
      setStartDate('01 Mei 2025');
      setEndDate('31 Mei 2025');
    } else {
      setStartDate('01 Mei 2025');
      setEndDate('15 Mei 2025');
    }
    onShowToast(`Filter periode diubah ke: ${id}`, 'info');
  };

  const handleApplyFilter = () => {
    onShowToast(`Rentang ${startDate} - ${endDate} berhasil diterapkan!`, 'success');
  };

  const handleExport = (format: 'pdf' | 'excel') => {
    setIsExportMenuOpen(false);
    onShowToast(
      `Menyiapkan berkas laporan rekapitulasi (${format.toUpperCase()})... Unduhan dimulai.`,
      'success'
    );
  };

  const handleSendWhatsApp = () => {
    onShowToast(
      'Ringkasan pendapatan & okupansi dikirimkan ke WhatsApp Bapak Surya Pratama (+62 811-9281-xxxx)',
      'success'
    );
  };

  const handleSendEmail = () => {
    onShowToast(
      'File PDF & XLSX laporan dikirim ke surya.pratama@thamrintower.id',
      'success'
    );
  };

  return (
    <div className="flex flex-col w-full space-y-3.5 pb-4">
      {/* Executive Profile & Location Banner */}
      <div className="relative overflow-hidden rounded-2xl bg-[#0f2042] p-4 text-white shadow-lg border border-[#1b3464]">
        <div className="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-[#316bf3]/20 blur-2xl pointer-events-none"></div>

        <div className="relative z-10 flex flex-col space-y-2">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <span className="relative flex h-2.5 w-2.5">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#6ffbbe] opacity-75"></span>
                <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#6ffbbe]"></span>
              </span>
              <span className="font-['JetBrains_Mono'] text-[11px] text-[#6ffbbe] tracking-wide uppercase font-bold">
                Real-Time Cloud Sync
              </span>
            </div>

            <button
              onClick={() => {
                if (window.confirm('Keluar dari dashboard eksekutif?')) {
                  onShowToast('Sesi owner ditutup', 'info');
                  onLogout();
                }
              }}
              className="flex items-center gap-1 px-2.5 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-white font-['JetBrains_Mono'] text-[11px] font-semibold transition-all active:scale-95"
            >
              <LogOut className="w-3.5 h-3.5" />
              <span>Keluar</span>
            </button>
          </div>

          <div className="pt-1 flex items-center gap-3">
            <div className="h-12 w-12 rounded-xl bg-gradient-to-tr from-[#0051d5] to-[#316bf3] p-0.5 shadow-md shrink-0">
              <img
                src={ASSETS.owner}
                alt="Bapak Surya Pratama"
                className="w-full h-full object-cover rounded-[10px]"
              />
            </div>
            <div className="min-w-0 flex-1">
              <p className="font-['JetBrains_Mono'] text-[10px] text-[#d9e2ff] uppercase tracking-wider font-bold">
                Dashboard Finansial &amp; Operasional
              </p>
              <h1 className="font-['Space_Grotesk'] text-[18px] font-bold text-white truncate">
                Bapak Surya Pratama
              </h1>
              <div className="flex items-center gap-1.5 text-[#d3e4fe] mt-0.5">
                <Building2 className="w-3.5 h-3.5 text-[#dbe1ff]" />
                <span className="font-['Hanken_Grotesk'] text-[12px] truncate">
                  Mall &amp; Office Tower Thamrin, Jakarta Pusat
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Custom Period Filter & Quick Presets Section */}
      <div className="rounded-2xl bg-white p-4 shadow-sm border border-[#e5eeff] flex flex-col space-y-3">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <Calendar className="w-4 h-4 text-[#0051d5]" />
            <h2 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
              Periode Rekapitulasi
            </h2>
          </div>
          <span className="font-['JetBrains_Mono'] text-[10px] text-[#75777f] px-2 py-0.5 rounded-full bg-[#eff4ff] font-bold border border-[#d3e4fe]">
            WIB (UTC+7)
          </span>
        </div>

        {/* Quick Pill Selector */}
        <div className="grid grid-cols-3 sm:grid-cols-5 gap-1.5">
          {periods.map((p) => (
            <button
              key={p.id}
              onClick={() => handlePeriodChange(p.id)}
              className={`py-1.5 px-2 rounded-xl text-center font-['JetBrains_Mono'] text-[11px] font-semibold transition-all ${
                selectedPeriod === p.id
                  ? 'bg-[#000922] text-white shadow-sm'
                  : 'bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0b1c30] border border-[#d3e4fe]'
              }`}
            >
              {p.label}
            </button>
          ))}
        </div>

        {/* Custom Range Picker Box */}
        <div className="bg-[#eff4ff] rounded-xl p-3 flex flex-col gap-2 border border-[#d3e4fe]">
          <div className="flex items-center justify-between text-[#45464e]">
            <span className="font-['JetBrains_Mono'] text-[11px] flex items-center gap-1.5">
              <Calendar className="w-3.5 h-3.5 text-[#0051d5]" />
              Rentang Tanggal Terpilih:
            </span>
            <span className="font-['JetBrains_Mono'] text-[11px] text-[#0051d5] font-bold">
              15 Hari Operasional
            </span>
          </div>

          <div className="flex items-center gap-2">
            <div className="flex-1 bg-white rounded-lg px-2.5 py-2 flex items-center justify-between border border-[#d3e4fe]">
              <span className="font-['JetBrains_Mono'] text-[12px] font-bold text-[#000922]">
                {startDate}
              </span>
              <Calendar className="w-4 h-4 text-[#75777f]" />
            </div>
            <span className="font-['Hanken_Grotesk'] text-[12px] text-[#75777f]">s/d</span>
            <div className="flex-1 bg-white rounded-lg px-2.5 py-2 flex items-center justify-between border border-[#d3e4fe]">
              <span className="font-['JetBrains_Mono'] text-[12px] font-bold text-[#000922]">
                {endDate}
              </span>
              <Calendar className="w-4 h-4 text-[#75777f]" />
            </div>
          </div>
        </div>

        {/* Action Buttons: Apply & Export */}
        <div className="grid grid-cols-2 gap-2 pt-1">
          <button
            onClick={handleApplyFilter}
            className="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#000922] text-white active:scale-98 transition-transform shadow-md font-['Space_Grotesk'] text-[13px] font-bold"
          >
            <RotateCw className="w-3.5 h-3.5 text-[#6ffbbe]" />
            <span>Terapkan Filter</span>
          </button>

          <div className="relative">
            <button
              onClick={() => setIsExportMenuOpen(!isExportMenuOpen)}
              className="w-full flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#eff4ff] text-[#000922] hover:bg-[#dce9ff] border border-[#d3e4fe] active:scale-98 transition-transform font-['Space_Grotesk'] text-[13px] font-bold"
            >
              <Download className="w-4 h-4 text-[#0051d5]" />
              <span>Ekspor Rekap</span>
              <ChevronDown className="w-3.5 h-3.5 text-[#75777f]" />
            </button>

            {isExportMenuOpen && (
              <div className="absolute right-0 top-12 w-48 bg-white rounded-2xl shadow-xl p-1.5 z-30 border border-[#d3e4fe] flex flex-col gap-1 animate-in fade-in">
                <button
                  onClick={() => handleExport('pdf')}
                  className="w-full text-left px-3 py-2 rounded-xl hover:bg-[#eff4ff] flex items-center gap-2 text-[#000922] text-[12px] font-semibold"
                >
                  <FileText className="w-4 h-4 text-[#ba1a1a]" />
                  <span>Format PDF (.pdf)</span>
                </button>
                <button
                  onClick={() => handleExport('excel')}
                  className="w-full text-left px-3 py-2 rounded-xl hover:bg-[#eff4ff] flex items-center gap-2 text-[#000922] text-[12px] font-semibold"
                >
                  <FileSpreadsheet className="w-4 h-4 text-[#009c6b]" />
                  <span>Format Excel (.xlsx)</span>
                </button>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Hero Financial Metric: Gross Revenue Card */}
      <div className="rounded-2xl bg-gradient-to-br from-[#0f2042] via-[#000922] to-[#0f2042] p-4 text-white shadow-xl border border-[#1b3464] relative overflow-hidden">
        <div className="flex items-center justify-between pb-1">
          <span className="font-['JetBrains_Mono'] text-[11px] text-[#d3e4fe] uppercase tracking-wider font-bold">
            Total Pendapatan Kotor
          </span>
          <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#002718] text-[#6ffbbe] font-['JetBrains_Mono'] text-[11px] font-bold border border-[#009c6b]">
            <TrendingUp className="w-3.5 h-3.5" />
            +14.2%
          </span>
        </div>

        <div className="flex items-baseline gap-1.5 my-1.5">
          <span className="font-['Space_Grotesk'] text-[18px] text-[#b6c6f1] font-bold">Rp</span>
          <span className="font-['Space_Grotesk'] text-[30px] font-bold text-white tracking-tight">
            48.750.000
          </span>
        </div>
        <p className="font-['Hanken_Grotesk'] text-[12px] text-[#7988b0]">
          vs Rp 42.688.000 pada 15 hari periode sebelumnya
        </p>

        {/* Payment Split Mini-Progress */}
        <div className="mt-3 pt-2 bg-white/5 rounded-xl p-2.5 flex flex-col gap-1.5 border border-white/10">
          <div className="flex justify-between items-center text-[11px] font-['JetBrains_Mono'] text-[#d3e4fe]">
            <span className="flex items-center gap-1.5 text-white font-bold">
              <QrCode className="w-3.5 h-3.5 text-[#316bf3]" />
              QRIS &amp; E-Money: 68%
            </span>
            <span className="flex items-center gap-1.5 text-[#d3e4fe]">
              <Banknote className="w-3.5 h-3.5 text-[#6ffbbe]" />
              Tunai: 32%
            </span>
          </div>

          <div className="w-full h-2 rounded-full bg-white/20 overflow-hidden flex">
            <div className="h-full bg-[#316bf3]" style={{ width: '68%' }}></div>
            <div className="h-full bg-[#4edea3]" style={{ width: '32%' }}></div>
          </div>

          <div className="flex justify-between font-['JetBrains_Mono'] text-[10px] text-[#7988b0]">
            <span>Rp 33.150.000</span>
            <span>Rp 15.600.000</span>
          </div>
        </div>
      </div>

      {/* Operational KPI Matrix (2 Cards) */}
      <div className="grid grid-cols-2 gap-2">
        {/* Volume Card */}
        <div className="rounded-2xl bg-white p-3.5 shadow-sm border border-[#e5eeff] flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-1">
              <span className="p-1.5 rounded-xl bg-[#eff4ff] text-[#0051d5] border border-[#d3e4fe]">
                <Car className="w-4 h-4" />
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#75777f] font-bold">
                15 Hari
              </span>
            </div>
            <p className="font-['Hanken_Grotesk'] text-[11px] text-[#75777f]">
              Volume Kendaraan
            </p>
            <p className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922]">
              14.320
            </p>
          </div>

          <div className="mt-2 pt-2 border-t border-[#e5eeff] space-y-1">
            <div className="flex items-center justify-between font-['JetBrains_Mono'] text-[10px] text-[#45464e]">
              <span className="flex items-center gap-1">
                <span className="w-2 h-2 rounded-full bg-[#0051d5]"></span> Mobil
              </span>
              <span className="font-bold text-[#000922]">58%</span>
            </div>
            <div className="flex items-center justify-between font-['JetBrains_Mono'] text-[10px] text-[#45464e]">
              <span className="flex items-center gap-1">
                <span className="w-2 h-2 rounded-full bg-[#009c6b]"></span> Motor
              </span>
              <span className="font-bold text-[#000922]">39%</span>
            </div>
            <div className="flex items-center justify-between font-['JetBrains_Mono'] text-[10px] text-[#45464e]">
              <span className="flex items-center gap-1">
                <span className="w-2 h-2 rounded-full bg-[#ba1a1a]"></span> Truk/Bus
              </span>
              <span className="font-bold text-[#000922]">3%</span>
            </div>
          </div>
        </div>

        {/* Duration Card */}
        <div className="rounded-2xl bg-white p-3.5 shadow-sm border border-[#e5eeff] flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-1">
              <span className="p-1.5 rounded-xl bg-[#eff4ff] text-[#0051d5] border border-[#d3e4fe]">
                <Clock className="w-4 h-4" />
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#009c6b] font-bold px-2 py-0.5 rounded-full bg-[#d3e4fe]">
                Optimal
              </span>
            </div>
            <p className="font-['Hanken_Grotesk'] text-[11px] text-[#75777f]">
              Rata-rata Durasi
            </p>
            <p className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922]">
              2j 40m
            </p>
          </div>

          <div className="mt-2 pt-2 border-t border-[#e5eeff] space-y-1">
            <div className="flex justify-between font-['JetBrains_Mono'] text-[10px]">
              <span className="text-[#45464e]">Perputaran Bay:</span>
              <span className="font-bold text-[#000922]">4.2x / hari</span>
            </div>
            <div className="flex justify-between font-['JetBrains_Mono'] text-[10px]">
              <span className="text-[#45464e]">Tarif Jam 1:</span>
              <span className="font-bold text-[#000922]">Rp 5.000</span>
            </div>
          </div>
        </div>
      </div>

      {/* Visual Analytics: Peak Hours & Revenue Rhythm */}
      <div className="rounded-2xl bg-white p-4 shadow-sm border border-[#e5eeff] flex flex-col space-y-3">
        <div className="flex items-start justify-between">
          <div>
            <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
              Ritme Jam Sibuk &amp; Fluktuasi
            </h3>
            <p className="font-['Hanken_Grotesk'] text-[11px] text-[#75777f]">
              Rata-rata traffic per jam operasional
            </p>
          </div>
          <div className="flex items-center gap-1 bg-[#ffdad6] px-2 py-1 rounded-xl text-[#ba1a1a] font-['JetBrains_Mono'] text-[10px] font-bold">
            <Flame className="w-3.5 h-3.5" />
            <span>Peak: 11:00 - 14:00</span>
          </div>
        </div>

        {/* Interactive Bar Chart */}
        <div className="w-full bg-[#eff4ff] rounded-2xl p-3 border border-[#d3e4fe]">
          <div className="h-36 w-full flex items-end justify-between gap-1.5 pt-4 pb-1">
            {hourlyData.map((d) => {
              const isPeak = d.peak;
              const isMax = d.max;
              const isHovered = activeHourlyBar === d.time;

              return (
                <div
                  key={d.time}
                  onClick={() => {
                    setActiveHourlyBar(d.time);
                    onShowToast(`Pukul ${d.time}: Okupansi ${d.value}`, 'info');
                  }}
                  className="flex-1 flex flex-col items-center gap-1 cursor-pointer group"
                >
                  <div
                    className={`w-full rounded-t-lg transition-all relative ${
                      isMax
                        ? 'bg-[#0051d5]'
                        : isPeak
                        ? 'bg-[#316bf3]'
                        : 'bg-[#b6c6f1] group-hover:bg-[#0051d5]'
                    }`}
                    style={{ height: `${d.height}%` }}
                  >
                    {(isPeak || isMax || isHovered) && (
                      <span className="absolute -top-4 left-1/2 -translate-x-1/2 font-['JetBrains_Mono'] text-[8px] font-bold text-[#0051d5] bg-white px-1 rounded shadow-xs">
                        {d.value}
                      </span>
                    )}
                  </div>
                  <span
                    className={`font-['JetBrains_Mono'] text-[9px] ${
                      isPeak || isMax ? 'font-bold text-[#0051d5]' : 'text-[#75777f]'
                    }`}
                  >
                    {d.time}
                  </span>
                </div>
              );
            })}
          </div>

          <div className="flex justify-between items-center pt-2 border-t border-[#d3e4fe] text-[#45464e]">
            <span className="font-['JetBrains_Mono'] text-[11px]">
              Total Kapasitas Terisi Puncak: <strong>98%</strong>
            </span>
            <span className="font-['JetBrains_Mono'] text-[11px] text-[#0051d5] font-bold">
              1.450 / 1.500 Slot
            </span>
          </div>
        </div>
      </div>

      {/* Operational Facilities & Gates Real-Time View */}
      <div className="rounded-2xl bg-white p-4 shadow-sm border border-[#e5eeff] space-y-3">
        <div className="flex items-center justify-between">
          <div>
            <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
              Rekapitulasi Shift &amp; Gerbang
            </h3>
            <p className="font-['Hanken_Grotesk'] text-[11px] text-[#75777f]">
              Audit kontribusi penerimaan lokasi
            </p>
          </div>
          <span className="font-['JetBrains_Mono'] text-[10px] text-[#0051d5] px-2 py-0.5 rounded-full bg-[#eff4ff] font-bold border border-[#d3e4fe]">
            3 Titik Akses
          </span>
        </div>

        {/* Gate Cards Stack */}
        <div className="space-y-2">
          {/* Gate 01 & 02 */}
          <div className="rounded-xl bg-[#eff4ff] p-3 flex flex-col space-y-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="w-7 h-7 rounded-lg bg-[#000922] text-white flex items-center justify-center font-['JetBrains_Mono'] text-[10px] font-bold">
                  G1-2
                </span>
                <div>
                  <h4 className="font-['Space_Grotesk'] text-[13px] font-bold text-[#000922]">
                    Gerbang Masuk 01 &amp; 02 (Utama)
                  </h4>
                  <p className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
                    Shift Pagi (06-14) &amp; Siang (14-22)
                  </p>
                </div>
              </div>
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                Rp 26.400.000
              </span>
            </div>
            <div className="flex justify-between text-[#45464e] font-['JetBrains_Mono'] text-[10px] pt-1 border-t border-[#d3e4fe]">
              <span>7.840 Transaksi (54.1%)</span>
              <span className="text-[#009c6b] font-bold">Audit Sesuai (100%)</span>
            </div>
          </div>

          {/* Gate 03 */}
          <div className="rounded-xl bg-[#eff4ff] p-3 flex flex-col space-y-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="w-7 h-7 rounded-lg bg-[#0051d5] text-white flex items-center justify-center font-['JetBrains_Mono'] text-[10px] font-bold">
                  G03
                </span>
                <div>
                  <h4 className="font-['Space_Grotesk'] text-[13px] font-bold text-[#000922]">
                    Gerbang Masuk 03 (Office Tower)
                  </h4>
                  <p className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
                    Dedicated Tenant Pass &amp; Valet Bay
                  </p>
                </div>
              </div>
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                Rp 15.100.000
              </span>
            </div>
            <div className="flex justify-between text-[#45464e] font-['JetBrains_Mono'] text-[10px] pt-1 border-t border-[#d3e4fe]">
              <span>4.520 Transaksi (31.0%)</span>
              <span className="text-[#009c6b] font-bold">Audit Sesuai (100%)</span>
            </div>
          </div>

          {/* Denda Tiket Hilang & Layanan Khusus */}
          <div className="rounded-xl bg-[#eff4ff] p-3 flex flex-col space-y-1.5 border border-[#d3e4fe]">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="w-7 h-7 rounded-lg bg-[#dce9ff] text-[#000922] flex items-center justify-center font-['JetBrains_Mono'] text-[10px] font-bold">
                  EXT
                </span>
                <div>
                  <h4 className="font-['Space_Grotesk'] text-[13px] font-bold text-[#000922]">
                    Denda Tiket Hilang &amp; Layanan Khusus
                  </h4>
                  <p className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
                    Denda kartu, overstay, cuci kendaraan mitra
                  </p>
                </div>
              </div>
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                Rp 7.250.000
              </span>
            </div>
            <div className="flex justify-between text-[#45464e] font-['JetBrains_Mono'] text-[10px] pt-1 border-t border-[#d3e4fe]">
              <span>1.960 Tiket / Tindakan (14.9%)</span>
              <span className="text-[#0051d5] font-bold">Tervalidasi Supervisor</span>
            </div>
          </div>
        </div>
      </div>

      {/* Operational Audit Note Card */}
      <div className="rounded-2xl bg-[#eff4ff] p-4 flex flex-col space-y-2 border border-[#d3e4fe]">
        <div className="flex items-center gap-2 text-[#000922]">
          <ShieldCheck className="w-5 h-5 text-[#0051d5]" />
          <h4 className="font-['Space_Grotesk'] text-[14px] font-bold">
            Catatan Audit &amp; Integritas Kas
          </h4>
        </div>
        <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e] leading-relaxed">
          Seluruh setoran fisik kasir shift pagi, siang, dan malam telah diverifikasi otomatis
          dengan saldo settlement QRIS Bank Mandiri &amp; BCA. Tidak ditemukan selisih minus pada
          periode terpilih ({startDate} - {endDate}).
        </p>

        {/* Instant Owner Dispatch Buttons */}
        <div className="pt-2 flex flex-col gap-2">
          <button
            onClick={handleSendWhatsApp}
            className="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#002718] text-[#6ffbbe] hover:bg-[#000922] active:scale-98 transition-all font-['Space_Grotesk'] text-[13px] font-bold shadow-sm border border-[#009c6b]"
          >
            <MessageSquare className="w-4 h-4 text-[#6ffbbe]" />
            <span>Kirim Ringkasan ke WhatsApp Owner</span>
          </button>

          <button
            onClick={handleSendEmail}
            className="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white text-[#000922] hover:bg-[#dce9ff] active:scale-98 transition-all font-['JetBrains_Mono'] text-[12px] font-semibold border border-[#d3e4fe]"
          >
            <Mail className="w-4 h-4 text-[#75777f]" />
            <span>Kirim File Laporan via Email (surya.pratama@thamrintower.id)</span>
          </button>
        </div>
      </div>
    </div>
  );
};
