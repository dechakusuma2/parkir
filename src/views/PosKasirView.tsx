import React, { useState } from 'react';
import { ParkingTicket, VehicleTariff } from '../types';
import {
  Car,
  Bike,
  Truck,
  Printer,
  LogOut,
  DollarSign,
  LogIn,
  QrCode,
  CreditCard,
  Banknote,
  CheckCircle2,
  AlertCircle,
  Delete,
  Volume2,
  Info,
  Layers,
  ArrowRight,
} from 'lucide-react';
import { QrisModal } from '../components/QrisModal';

interface PosKasirViewProps {
  onShowToast: (message: string, type?: 'success' | 'info' | 'error') => void;
  onLogout: () => void;
}

export const PosKasirView: React.FC<PosKasirViewProps> = ({
  onShowToast,
  onLogout,
}) => {
  const [activeTab, setActiveTab] = useState<'checkin' | 'checkout'>('checkin');

  // Check-in state
  const [selectedCategory, setSelectedCategory] = useState<'motor' | 'mobil' | 'truk'>('motor');
  const [plateInput, setPlateInput] = useState('B 4821 KLO');
  const [ticketNumber, setTicketNumber] = useState('SP-202505-0891');
  const [entryTime, setEntryTime] = useState('10:24:18 WIB');
  const [isBarrierOpen, setIsBarrierOpen] = useState(false);
  const [isPrinting, setIsPrinting] = useState(false);

  // Check-out state
  const [searchTicket, setSearchTicket] = useState('SP-202505-0842');
  const [checkoutData, setCheckoutData] = useState({
    ticket: 'SP-202505-0842',
    plate: 'B 9912 TZV',
    vehicleType: 'MOBIL',
    entryTime: '08:09 WIB',
    exitTime: '10:24 WIB',
    duration: '2 Jam 15 Mnt',
    calculationNote: '2 Jam x Rp 5.000 (Toleransi 15 Mnt)',
    totalFee: 10000,
  });
  const [paymentMethod, setPaymentMethod] = useState<'cash' | 'qris' | 'emoney'>('cash');
  const [showQrisModal, setShowQrisModal] = useState(false);
  const [checkoutSuccess, setCheckoutSuccess] = useState(false);

  // Metrics
  const [vehicleCount, setVehicleCount] = useState(142);
  const [cashierRevenue, setCashierRevenue] = useState(680);

  const categories = [
    {
      id: 'motor' as const,
      name: 'Motor',
      rate: 'Rp 2.000',
      icon: Bike,
      fee: 2000,
    },
    {
      id: 'mobil' as const,
      name: 'Mobil',
      rate: 'Rp 5.000',
      icon: Car,
      fee: 5000,
    },
    {
      id: 'truk' as const,
      name: 'Box / Truk',
      rate: 'Rp 10.000',
      icon: Truck,
      fee: 10000,
    },
  ];

  const handlePlatePrefix = (prefix: string) => {
    setPlateInput(prefix);
  };

  const handleSimulateCheckin = () => {
    const rawPlate = plateInput.trim() || 'B 1234 ABC';
    const cleanPlate = rawPlate.toUpperCase();
    const newNum = 'SP-202505-' + Math.floor(1000 + Math.random() * 9000);
    const now = new Date();
    const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(
      now.getMinutes()
    ).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')} WIB`;

    setIsPrinting(true);
    setTimeout(() => {
      setTicketNumber(newNum);
      setEntryTime(timeStr);
      setPlateInput(cleanPlate);
      setVehicleCount((prev) => prev + 1);
      setIsPrinting(false);
      setIsBarrierOpen(true);
      onShowToast(`Karcis ${newNum} dicetak. Palang Barrier Terbuka!`, 'success');

      setTimeout(() => {
        setIsBarrierOpen(false);
      }, 4000);
    }, 600);
  };

  const handleCalculateCheckout = () => {
    onShowToast(`Tiket ${searchTicket} berhasil divalidasi`, 'info');
  };

  const handleProcessCheckout = () => {
    if (paymentMethod === 'qris') {
      setShowQrisModal(true);
      return;
    }
    completeCheckoutPayment();
  };

  const completeCheckoutPayment = () => {
    setCheckoutSuccess(true);
    setCashierRevenue((prev) => prev + checkoutData.totalFee / 1000);
    setIsBarrierOpen(true);
    onShowToast(
      `Pembayaran Rp ${checkoutData.totalFee.toLocaleString(
        'id-ID'
      )} diterima (${paymentMethod.toUpperCase()}). Palang keluar dibuka!`,
      'success'
    );

    setTimeout(() => {
      setIsBarrierOpen(false);
    }, 4500);
  };

  const handlePrintReceiptOnly = () => {
    onShowToast('Struk tanda bukti parkir berhasil dicetak ke printer POS Bluetooth', 'info');
  };

  return (
    <div className="flex flex-col w-full space-y-3.5 pb-3">
      {/* Operator Session Header */}
      <div className="bg-[#0f2042] text-white rounded-2xl p-3.5 shadow-md flex flex-col gap-2 border border-[#1b3464]">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className="flex h-2.5 w-2.5 rounded-full bg-[#6ffbbe] animate-pulse"></span>
            <span className="font-['Space_Grotesk'] text-[15px] font-bold text-white tracking-tight">
              Gerbang Masuk/Keluar 01
            </span>
          </div>
          <button
            onClick={() => {
              if (window.confirm('Akhiri shift saat ini dan cetak rekap kasir?')) {
                onShowToast('Shift ditutup. Total kas dicetak ke printer POS.', 'info');
                onLogout();
              }
            }}
            className="px-2.5 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-[#d3e4fe] text-[12px] font-['JetBrains_Mono'] flex items-center gap-1 transition-all"
          >
            <LogOut className="w-3.5 h-3.5" />
            <span>Keluar</span>
          </button>
        </div>

        <div className="flex flex-wrap items-center justify-between text-[#7988b0] text-[11px] font-['JetBrains_Mono'] pt-1 border-t border-white/10">
          <div className="flex items-center gap-1.5 text-white">
            <span className="font-semibold text-white">Budi Santoso</span>
            <span className="opacity-40">•</span>
            <span className="text-[#d3e4fe]">Shift Pagi (07:00 - 15:00)</span>
          </div>
          <div className="flex items-center gap-1 text-[#6ffbbe] font-semibold">
            <Printer className="w-3.5 h-3.5" />
            <span>Thermal POS Ready</span>
          </div>
        </div>
      </div>

      {/* Real-Time Metrics Strip */}
      <div className="grid grid-cols-2 gap-2">
        <div className="bg-white rounded-2xl p-3 shadow-sm flex items-center gap-3 border border-[#e5eeff]">
          <div className="w-10 h-10 rounded-xl bg-[#eff4ff] flex items-center justify-center text-[#0051d5] border border-[#d3e4fe]">
            <Car className="w-5 h-5" />
          </div>
          <div className="flex flex-col min-w-0">
            <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
              Kendaraan Hari Ini
            </span>
            <div className="flex items-baseline gap-1">
              <span className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922]">
                {vehicleCount}
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#009c6b] font-bold">
                +18/jam
              </span>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl p-3 shadow-sm flex items-center gap-3 border border-[#e5eeff]">
          <div className="w-10 h-10 rounded-xl bg-[#e5eeff] flex items-center justify-center text-[#009c6b] border border-[#b6c6f1]">
            <DollarSign className="w-5 h-5" />
          </div>
          <div className="flex flex-col min-w-0">
            <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
              Penerimaan Kasir
            </span>
            <div className="flex items-baseline gap-1">
              <span className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922]">
                {cashierRevenue}k
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
                IDR
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* Barrier Gate Live Status Indicator Banner */}
      {isBarrierOpen && (
        <div className="p-3 bg-[#002718] text-[#6ffbbe] rounded-2xl border border-[#009c6b] shadow-md flex items-center justify-between animate-bounce">
          <div className="flex items-center gap-2">
            <span className="w-3 h-3 rounded-full bg-[#6ffbbe] animate-ping"></span>
            <span className="font-['Space_Grotesk'] text-[14px] font-bold">
              PALANG BARRIER GATE DIBUKA!
            </span>
          </div>
          <span className="font-['JetBrains_Mono'] text-[11px] bg-[#009c6b] text-white px-2 py-0.5 rounded-full font-bold">
            Sensor Clear
          </span>
        </div>
      )}

      {/* Tab Toggle Bar */}
      <div className="bg-[#dce9ff] p-1 rounded-2xl grid grid-cols-2 gap-1 border border-[#d3e4fe]">
        <button
          onClick={() => setActiveTab('checkin')}
          className={`min-h-[46px] rounded-xl font-['Space_Grotesk'] text-[14px] flex items-center justify-center gap-2 transition-all font-bold ${
            activeTab === 'checkin'
              ? 'bg-white text-[#000922] shadow-sm'
              : 'text-[#45464e] hover:text-[#0b1c30]'
          }`}
        >
          <LogIn className="w-4 h-4 text-[#0051d5]" />
          <span>Tiket Masuk</span>
        </button>
        <button
          onClick={() => setActiveTab('checkout')}
          className={`min-h-[46px] rounded-xl font-['Space_Grotesk'] text-[14px] flex items-center justify-center gap-2 transition-all font-bold ${
            activeTab === 'checkout'
              ? 'bg-white text-[#000922] shadow-sm'
              : 'text-[#45464e] hover:text-[#0b1c30]'
          }`}
        >
          <CreditCard className="w-4 h-4 text-[#0051d5]" />
          <span>Keluar &amp; Bayar</span>
        </button>
      </div>

      {/* VIEW 1: CHECK-IN (TIKET MASUK) */}
      {activeTab === 'checkin' && (
        <div className="flex flex-col gap-3">
          {/* Vehicle Selection Matrix */}
          <div className="bg-white p-3.5 rounded-2xl shadow-sm border border-[#e5eeff] space-y-2">
            <div className="flex items-center justify-between">
              <span className="font-['Space_Grotesk'] text-[14px] text-[#000922] font-bold">
                1. Pilih Kategori Kendaraan
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded bg-[#eff4ff] text-[#0051d5] font-semibold border border-[#d3e4fe]">
                Shortcut [1-3]
              </span>
            </div>

            <div className="grid grid-cols-3 gap-2">
              {categories.map((cat) => {
                const Icon = cat.icon;
                const isSelected = selectedCategory === cat.id;

                return (
                  <button
                    key={cat.id}
                    type="button"
                    onClick={() => setSelectedCategory(cat.id)}
                    className={`min-h-[74px] p-2 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all ${
                      isSelected
                        ? 'bg-[#0051d5] text-white shadow-md scale-[1.02]'
                        : 'bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0b1c30] border border-[#d3e4fe]'
                    }`}
                  >
                    <Icon className="w-6 h-6 mb-0.5" />
                    <span className="font-['Space_Grotesk'] text-[13px] font-bold leading-tight">
                      {cat.name}
                    </span>
                    <span
                      className={`font-['JetBrains_Mono'] text-[10px] ${
                        isSelected ? 'text-white/90' : 'text-[#75777f]'
                      }`}
                    >
                      {cat.rate}
                    </span>
                  </button>
                );
              })}
            </div>
          </div>

          {/* License Plate Quick Input */}
          <div className="bg-white p-3.5 rounded-2xl shadow-sm border border-[#e5eeff] space-y-2">
            <div className="flex items-center justify-between">
              <label
                htmlFor="plate-input"
                className="font-['Space_Grotesk'] text-[14px] text-[#000922] font-bold"
              >
                2. Plat Nomor Kendaraan
              </label>
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#0051d5] font-semibold">
                Auto-Capitalized
              </span>
            </div>

            <div className="relative flex items-center">
              <span className="absolute left-3.5 font-['JetBrains_Mono'] text-[14px] text-[#75777f] font-bold">
                123
              </span>
              <input
                id="plate-input"
                type="text"
                value={plateInput}
                onChange={(e) => setPlateInput(e.target.value.toUpperCase())}
                placeholder="B 1234 XYZ"
                className="w-full h-14 pl-12 pr-12 rounded-xl bg-[#eff4ff] text-[#000922] font-['JetBrains_Mono'] text-[20px] tracking-wider font-bold focus:outline-none focus:ring-2 focus:ring-[#0051d5] border border-[#d3e4fe]"
              />
              <button
                type="button"
                onClick={() => setPlateInput('')}
                className="absolute right-3 w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#75777f] hover:text-[#ba1a1a] shadow-xs border border-[#d3e4fe]"
              >
                <Delete className="w-4 h-4" />
              </button>
            </div>

            {/* Quick Plate Preset Badges */}
            <div className="flex items-center gap-1.5 overflow-x-auto pt-1 no-scrollbar">
              <span className="font-['JetBrains_Mono'] text-[10px] text-[#75777f] shrink-0 font-semibold">
                Prefix:
              </span>
              {[
                { code: 'B ', label: 'B (JKT)' },
                { code: 'D ', label: 'D (BDG)' },
                { code: 'F ', label: 'F (BGR)' },
                { code: 'L ', label: 'L (SBY)' },
              ].map((p) => (
                <button
                  key={p.code}
                  type="button"
                  onClick={() => handlePlatePrefix(p.code)}
                  className="px-2.5 py-1 rounded-lg bg-[#eff4ff] hover:bg-[#dce9ff] font-['JetBrains_Mono'] text-[11px] font-bold text-[#000922] border border-[#d3e4fe] active:scale-95 transition-transform"
                >
                  {p.label}
                </button>
              ))}
            </div>
          </div>

          {/* Live Action: Print Ticket & Open Gate */}
          <div className="flex flex-col gap-2">
            <button
              type="button"
              disabled={isPrinting}
              onClick={handleSimulateCheckin}
              className="w-full min-h-[56px] rounded-2xl bg-[#000922] hover:bg-[#0f2042] active:scale-[0.99] text-white shadow-lg flex items-center justify-center gap-2 font-['Space_Grotesk'] text-[16px] font-bold transition-all"
            >
              {isPrinting ? (
                <>
                  <span className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                  <span>Mencetak Karcis &amp; Verifikasi IoT...</span>
                </>
              ) : (
                <>
                  <Printer className="w-5 h-5 text-[#6ffbbe]" />
                  <span>Cetak Karcis &amp; Buka Barrier</span>
                </>
              )}
            </button>

            <div className="flex items-center justify-between text-[11px] font-['JetBrains_Mono'] text-[#75777f] px-1">
              <span>
                Gate Sensor: <strong className="text-[#000922]">Mendeteksi 1 Unit</strong>
              </span>
              <span>
                Printer: <strong className="text-[#009c6b]">Online (Paper 94%)</strong>
              </span>
            </div>
          </div>

          {/* Thermal Ticket Simulation Preview Card */}
          <div className="bg-white p-3.5 rounded-2xl shadow-sm border border-[#e5eeff] space-y-2">
            <div className="flex items-center justify-between">
              <span className="font-['JetBrains_Mono'] text-[11px] uppercase tracking-wider text-[#0051d5] font-bold flex items-center gap-1.5">
                <CheckCircle2 className="w-4 h-4 text-[#009c6b]" />
                Karcis Fisik Dikeluarkan
              </span>
              <button
                type="button"
                onClick={handleSimulateCheckin}
                className="font-['JetBrains_Mono'] text-[11px] text-[#0051d5] hover:underline flex items-center gap-1 font-bold"
              >
                <Printer className="w-3.5 h-3.5" />
                Cetak Ulang
              </button>
            </div>

            {/* Realistic Thermal Paper Visual */}
            <div className="bg-[#f8f9ff] p-4 rounded-xl flex flex-col items-center text-center gap-1 text-[#000922] border border-[#d3e4fe] shadow-inner font-mono">
              <span className="font-['Space_Grotesk'] text-[16px] font-bold tracking-tight text-[#000922]">
                SMARTPARKIR SYSTEM
              </span>
              <span className="text-[11px] text-[#75777f]">
                Mall Grand Metropolitan Lt. GF
              </span>
              <div className="w-full h-px bg-[#d3e4fe] my-1"></div>

              <div className="w-full grid grid-cols-2 text-left text-[11px] gap-y-1 font-['JetBrains_Mono']">
                <span className="text-[#75777f]">No. Karcis:</span>
                <span className="text-right font-bold text-[#000922]">{ticketNumber}</span>

                <span className="text-[#75777f]">Waktu Masuk:</span>
                <span className="text-right font-medium">{entryTime}</span>

                <span className="text-[#75777f]">Kategori / Plat:</span>
                <span className="text-right font-bold text-[#000922]">
                  {selectedCategory.toUpperCase()} • {plateInput || 'B 4821 KLO'}
                </span>

                <span className="text-[#75777f]">Petugas / Gate:</span>
                <span className="text-right">Budi S. / In-01</span>
              </div>

              {/* Barcode representation */}
              <div className="w-full py-2.5 flex flex-col items-center justify-center gap-1">
                <div className="flex items-center justify-center gap-[2.5px] h-9 w-4/5 px-2 bg-white rounded border border-[#d3e4fe]">
                  <span className="h-7 w-1 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-1.5 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-2 bg-black"></span>
                  <span className="h-7 w-1 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-1.5 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-2 bg-black"></span>
                  <span className="h-7 w-1 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-1 bg-black"></span>
                  <span className="h-7 w-1.5 bg-black"></span>
                  <span className="h-7 w-0.5 bg-black"></span>
                  <span className="h-7 w-1 bg-black"></span>
                  <span className="h-7 w-2 bg-black"></span>
                </div>
                <span className="text-[10px] tracking-widest text-[#75777f]">
                  * {ticketNumber.replace(/-/g, '')} *
                </span>
              </div>

              <div className="w-full h-px bg-[#d3e4fe] my-1"></div>
              <p className="text-[10px] text-[#75777f] font-sans">
                Simpan karcis ini baik-baik. Denda tiket hilang Rp 25.000.
              </p>
            </div>
          </div>
        </div>
      )}

      {/* VIEW 2: CHECK-OUT (KELUAR & BAYAR) */}
      {activeTab === 'checkout' && (
        <div className="flex flex-col gap-3">
          {/* Scan / Search Barcode */}
          <div className="bg-white p-3.5 rounded-2xl shadow-sm border border-[#e5eeff] space-y-2">
            <label
              htmlFor="checkout-ticket-input"
              className="font-['Space_Grotesk'] text-[14px] text-[#000922] font-bold"
            >
              1. Scan Barcode / Ketik Nomor Tiket
            </label>
            <div className="flex gap-2">
              <div className="relative flex-1">
                <input
                  id="checkout-ticket-input"
                  type="text"
                  value={searchTicket}
                  onChange={(e) => setSearchTicket(e.target.value.toUpperCase())}
                  placeholder="SP-202505-0842"
                  className="w-full h-12 pl-3 pr-10 rounded-xl bg-[#eff4ff] text-[#000922] font-['JetBrains_Mono'] text-[15px] uppercase font-bold focus:outline-none border border-[#d3e4fe]"
                />
                <button
                  type="button"
                  onClick={() => setSearchTicket('SP-202505-0842')}
                  className="absolute right-2 top-2 w-8 h-8 rounded-lg text-[#75777f] hover:text-[#0051d5] flex items-center justify-center"
                >
                  <QrCode className="w-5 h-5" />
                </button>
              </div>
              <button
                type="button"
                onClick={handleCalculateCheckout}
                className="px-4 h-12 rounded-xl bg-[#0051d5] hover:bg-[#003ea8] text-white font-['Space_Grotesk'] text-[14px] font-bold flex items-center justify-center shadow-sm"
              >
                Cari
              </button>
            </div>
          </div>

          {/* Active Calculation Card */}
          <div className="bg-white p-3.5 rounded-2xl shadow-sm border border-[#e5eeff] space-y-3">
            <div className="flex items-center justify-between pb-1 border-b border-[#e5eeff]">
              <div className="flex items-center gap-2">
                <span className="px-2 py-0.5 rounded-md bg-[#0f2042] text-white font-['JetBrains_Mono'] text-[11px] font-bold">
                  {checkoutData.vehicleType}
                </span>
                <span className="font-['JetBrains_Mono'] text-[16px] font-bold text-[#000922]">
                  {checkoutData.plate}
                </span>
              </div>
              <span className="font-['JetBrains_Mono'] text-[11px] px-2.5 py-0.5 rounded-full bg-[#d3e4fe] text-[#0051d5] font-bold border border-[#b6c6f1]">
                Terverifikasi
              </span>
            </div>

            {/* Time & Duration Breakdown Table */}
            <div className="grid grid-cols-2 gap-2 bg-[#eff4ff] p-2 rounded-xl font-['JetBrains_Mono'] text-[11px] border border-[#d3e4fe]">
              <div className="flex flex-col p-2 bg-white rounded-lg border border-[#e5eeff]">
                <span className="text-[#75777f]">Waktu Masuk</span>
                <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                  {checkoutData.entryTime}
                </span>
                <span className="text-[#75777f] text-[10px]">Hari ini</span>
              </div>
              <div className="flex flex-col p-2 bg-white rounded-lg border border-[#e5eeff]">
                <span className="text-[#75777f]">Waktu Keluar</span>
                <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                  {checkoutData.exitTime}
                </span>
                <span className="text-[#0051d5] font-bold text-[10px]">
                  Durasi: {checkoutData.duration}
                </span>
              </div>
            </div>

            {/* Amount Summary */}
            <div className="p-3 rounded-xl bg-[#e5eeff] flex items-center justify-between border border-[#b6c6f1]">
              <div className="flex flex-col">
                <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                  Tarif Dasar + Jam Tambahan
                </span>
                <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                  {checkoutData.calculationNote}
                </span>
              </div>
              <div className="flex flex-col items-end">
                <span className="font-['JetBrains_Mono'] text-[10px] text-[#75777f]">
                  Total Tagihan
                </span>
                <span className="font-['Space_Grotesk'] text-[20px] font-bold text-[#000922]">
                  Rp {checkoutData.totalFee.toLocaleString('id-ID')}
                </span>
              </div>
            </div>

            {/* Payment Method Selector */}
            <div className="flex flex-col gap-1.5 pt-1">
              <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                2. Cara Bayar
              </span>
              <div className="grid grid-cols-3 gap-2">
                {[
                  { id: 'cash' as const, label: 'Tunai', icon: Banknote },
                  { id: 'qris' as const, label: 'QRIS', icon: QrCode },
                  { id: 'emoney' as const, label: 'E-Money', icon: CreditCard },
                ].map((m) => {
                  const Icon = m.icon;
                  const isSelected = paymentMethod === m.id;
                  return (
                    <button
                      key={m.id}
                      type="button"
                      onClick={() => setPaymentMethod(m.id)}
                      className={`h-12 rounded-xl flex items-center justify-center gap-1.5 font-['Space_Grotesk'] text-[13px] font-bold transition-all ${
                        isSelected
                          ? 'bg-[#000922] text-white shadow-sm'
                          : 'bg-[#eff4ff] text-[#0b1c30] hover:bg-[#dce9ff] border border-[#d3e4fe]'
                      }`}
                    >
                      <Icon className="w-4 h-4 text-[#0051d5]" />
                      <span>{m.label}</span>
                    </button>
                  );
                })}
              </div>
            </div>
          </div>

          {/* Checkout Trigger Buttons */}
          <div className="flex flex-col gap-2">
            <button
              type="button"
              onClick={handleProcessCheckout}
              className="w-full min-h-[56px] rounded-2xl bg-[#002718] text-[#6ffbbe] hover:bg-[#000922] font-['Space_Grotesk'] text-[16px] font-bold shadow-lg flex items-center justify-center gap-2 transition-all active:scale-[0.99] border border-[#009c6b]"
            >
              <CheckCircle2 className="w-5 h-5 text-[#6ffbbe]" />
              <span>Bayar Lunas &amp; Buka Palang</span>
            </button>

            <button
              type="button"
              onClick={handlePrintReceiptOnly}
              className="w-full min-h-[44px] rounded-xl bg-[#eff4ff] hover:bg-[#dce9ff] text-[#000922] font-['Space_Grotesk'] text-[13px] font-bold flex items-center justify-center gap-2 border border-[#d3e4fe]"
            >
              <Printer className="w-4 h-4 text-[#0051d5]" />
              <span>Cetak Struk Transaksi Saja</span>
            </button>
          </div>

          {/* Alert Status Feedback */}
          {checkoutSuccess && (
            <div className="p-3.5 rounded-2xl bg-[#eff4ff] text-[#000922] flex items-center gap-3 shadow-sm border border-[#009c6b]">
              <CheckCircle2 className="w-6 h-6 text-[#009c6b] shrink-0" />
              <div className="flex flex-col min-w-0">
                <span className="font-['Space_Grotesk'] text-[14px] font-bold">
                  Transaksi Berhasil &amp; Palang Terbuka!
                </span>
                <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                  Struk keluar otomatis tercetak via thermal port Bluetooth.
                </span>
              </div>
            </div>
          )}
        </div>
      )}

      {/* Operational Field Quick Info */}
      <div className="bg-[#eff4ff] p-3 rounded-2xl flex items-center justify-between text-[12px] text-[#45464e] border border-[#d3e4fe]">
        <div className="flex items-center gap-1.5">
          <Info className="w-4 h-4 text-[#0051d5]" />
          <span>Bantuan teknis gate macet hubungi SPV (Ext. 402)</span>
        </div>
        <span className="font-['JetBrains_Mono'] text-[11px] font-bold text-[#75777f]">
          v2.4 POS-Mobile
        </span>
      </div>

      {/* QRIS Modal */}
      <QrisModal
        isOpen={showQrisModal}
        onClose={() => setShowQrisModal(false)}
        amount={checkoutData.totalFee}
        ticketNumber={checkoutData.ticket}
        plateNumber={checkoutData.plate}
        onPaymentSuccess={completeCheckoutPayment}
      />
    </div>
  );
};
