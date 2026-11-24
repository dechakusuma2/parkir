import React, { useState, useEffect } from 'react';
import { X, QrCode, CheckCircle2, ShieldCheck } from 'lucide-react';

interface QrisModalProps {
  isOpen: boolean;
  onClose: () => void;
  amount: number;
  ticketNumber: string;
  plateNumber: string;
  onPaymentSuccess: () => void;
}

/** Menampilkan ringkasan pembayaran QRIS dan simulasi batas waktu pemindaian. */
export const QrisModal: React.FC<QrisModalProps> = ({
  isOpen,
  onClose,
  amount,
  ticketNumber,
  plateNumber,
  onPaymentSuccess,
}) => {
  const [countdown, setCountdown] = useState(180);
  const [isProcessing, setIsProcessing] = useState(false);

  // Memulai ulang hitung mundur saat modal dibuka dan membersihkan timer saat ditutup.
  useEffect(() => {
    if (!isOpen) return;
    setCountdown(180);
    setIsProcessing(false);
    const interval = setInterval(() => {
      setCountdown((prev) => (prev > 0 ? prev - 1 : 0));
    }, 1000);
    return () => clearInterval(interval);
  }, [isOpen]);

  if (!isOpen) return null;

  // Menyusun sisa waktu menjadi format menit dan detik untuk ditampilkan.
  const minutes = Math.floor(countdown / 60);
  const seconds = countdown % 60;
  const timeFormatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

  // Menyimulasikan pembayaran berhasil lalu memberi tahu pemanggil.
  const handleSimulateScan = () => {
    setIsProcessing(true);
    setTimeout(() => {
      setIsProcessing(false);
      onPaymentSuccess();
      onClose();
    }, 1200);
  };

  return (
    <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4 animate-in fade-in duration-200">
      <div className="w-full max-w-sm bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff] text-center">
        {/* Header */}
        <div className="flex items-center justify-between pb-2 border-b border-[#e5eeff]">
          <div className="flex items-center gap-1.5 text-left">
            <QrCode className="w-5 h-5 text-[#0051d5]" />
            <span className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
              QRIS Nasional
            </span>
          </div>
          <button
            onClick={onClose}
            className="w-8 h-8 rounded-lg flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff]"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Payment Details */}
        <div className="my-3 py-2 bg-[#eff4ff] rounded-xl border border-[#d3e4fe]">
          <span className="text-[11px] font-['JetBrains_Mono'] text-[#75777f] uppercase">
            Total Tagihan Parkir
          </span>
          <p className="font-['Space_Grotesk'] text-[24px] font-bold text-[#000922]">
            Rp {amount.toLocaleString('id-ID')}
          </p>
          <div className="flex justify-center items-center gap-2 text-[11px] font-['JetBrains_Mono'] text-[#45464e] mt-0.5">
            <span>{ticketNumber}</span>
            <span>•</span>
            <span className="font-bold text-[#0051d5]">{plateNumber}</span>
          </div>
        </div>

        {/* QR Pattern visual */}
        <div className="p-4 bg-white border-2 border-dashed border-[#b6c6f1] rounded-2xl flex flex-col items-center justify-center relative">
          <div className="w-44 h-44 bg-[#f8f9ff] p-2 rounded-xl flex items-center justify-center border border-[#d3e4fe]">
            {/* High visual fidelity QR simulation */}
            <svg viewBox="0 0 100 100" className="w-full h-full text-[#000922]">
              <rect x="0" y="0" width="30" height="30" fill="currentColor" rx="4" />
              <rect x="5" y="5" width="20" height="20" fill="white" rx="2" />
              <rect x="9" y="9" width="12" height="12" fill="currentColor" rx="1" />

              <rect x="70" y="0" width="30" height="30" fill="currentColor" rx="4" />
              <rect x="75" y="5" width="20" height="20" fill="white" rx="2" />
              <rect x="79" y="9" width="12" height="12" fill="currentColor" rx="1" />

              <rect x="0" y="70" width="30" height="30" fill="currentColor" rx="4" />
              <rect x="5" y="75" width="20" height="20" fill="white" rx="2" />
              <rect x="9" y="79" width="12" height="12" fill="currentColor" rx="1" />

              {/* Data dots */}
              <circle cx="40" cy="15" r="3" fill="currentColor" />
              <circle cx="55" cy="15" r="3" fill="currentColor" />
              <circle cx="48" cy="25" r="3" fill="currentColor" />
              <circle cx="15" cy="45" r="3" fill="currentColor" />
              <circle cx="25" cy="55" r="3" fill="currentColor" />
              <circle cx="50" cy="50" r="5" fill="#0051d5" />
              <circle cx="40" cy="65" r="3" fill="currentColor" />
              <circle cx="55" cy="75" r="3" fill="currentColor" />
              <circle cx="70" cy="45" r="3" fill="currentColor" />
              <circle cx="85" cy="55" r="3" fill="currentColor" />
              <circle cx="75" cy="70" r="3" fill="currentColor" />
              <circle cx="88" cy="85" r="3" fill="currentColor" />
            </svg>
          </div>

          <div className="mt-2 text-[11px] font-['JetBrains_Mono'] text-[#75777f] flex items-center gap-1.5">
            <span className="w-2 h-2 rounded-full bg-[#009c6b] animate-ping"></span>
            <span>Batas Waktu: <strong className="text-[#ba1a1a]">{timeFormatted}</strong></span>
          </div>
        </div>

        <div className="mt-3 flex items-center justify-center gap-1 text-[11px] text-[#45464e]">
          <ShieldCheck className="w-3.5 h-3.5 text-[#009c6b]" />
          <span>BCA, Mandiri, BRI, GoPay, OVO, ShopeePay</span>
        </div>

        {/* Fast Simulator Button */}
        <div className="mt-4 flex flex-col gap-2">
          <button
            onClick={handleSimulateScan}
            disabled={isProcessing}
            className="w-full py-3 bg-[#0051d5] text-white rounded-xl font-['Space_Grotesk'] font-bold text-sm shadow-md hover:bg-[#003ea8] active:scale-[0.99] transition-all flex items-center justify-center gap-2"
          >
            {isProcessing ? (
              <>
                <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span>Memproses Settlement Bank...</span>
              </>
            ) : (
              <>
                <CheckCircle2 className="w-4 h-4" />
                <span>Simulasikan Customer Scan &amp; Bayar</span>
              </>
            )}
          </button>
          <button
            onClick={onClose}
            className="w-full py-2 text-xs font-semibold text-[#75777f] hover:text-[#000922]"
          >
            Batal / Pilih Metode Lain
          </button>
        </div>
      </div>
    </div>
  );
};
