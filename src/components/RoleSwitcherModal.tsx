import React from 'react';
import { UserRole, NavTab } from '../types';
import { ASSETS } from '../data/mockData';
import { Check, ShieldCheck, User, BarChart2, X, ArrowRight } from 'lucide-react';

interface RoleSwitcherModalProps {
  isOpen: boolean;
  onClose: () => void;
  activeRole: UserRole;
  onSelectRole: (role: UserRole, targetTab?: NavTab) => void;
}

export const RoleSwitcherModal: React.FC<RoleSwitcherModalProps> = ({
  isOpen,
  onClose,
  activeRole,
  onSelectRole,
}) => {
  if (!isOpen) return null;

  const roles = [
    {
      id: 'petugas' as UserRole,
      title: 'Petugas Gerbang & POS',
      subtitle: 'Entri karcis masuk, cetak thermal barcode, transaksi keluar bayar tunai/QRIS.',
      targetTab: 'pos-petugas' as NavTab,
      icon: User,
      avatar: ASSETS.avatar,
      badge: 'Operator Lapangan',
      badgeColor: 'bg-[#eff4ff] text-[#0051d5]',
    },
    {
      id: 'admin' as UserRole,
      title: 'Administrator Master',
      subtitle: 'Konfigurasi tarif parkir, manajemen area IoT, denah, pengguna & audit trail.',
      targetTab: 'admin-master' as NavTab,
      icon: ShieldCheck,
      avatar: ASSETS.avatar,
      badge: 'Super Admin',
      badgeColor: 'bg-[#0f2042] text-white',
    },
    {
      id: 'owner' as UserRole,
      title: 'Direksi & Owner Aset',
      subtitle: 'Monitoring omzet pendapatan real-time, audit rekonsiliasi kas, dan ekspor laporan.',
      targetTab: 'owner-rekap' as NavTab,
      icon: BarChart2,
      avatar: ASSETS.owner,
      badge: 'Executive',
      badgeColor: 'bg-[#d3e4fe] text-[#0051d5]',
    },
  ];

  return (
    <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4 animate-in fade-in duration-200">
      <div className="w-full max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff] max-h-[85vh] overflow-y-auto">
        <div className="flex items-center justify-between pb-3 border-b border-[#e5eeff]">
          <div>
            <h3 className="font-['Space_Grotesk'] text-[18px] font-bold text-[#000922]">
              Pilih Otorisasi Pengguna
            </h3>
            <p className="font-['Hanken_Grotesk'] text-[13px] text-[#45464e]">
              Beralih peran untuk menguji setiap hak akses sistem
            </p>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-xl flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff] hover:text-[#000922] transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="flex flex-col gap-2.5 py-4">
          {roles.map((r) => {
            const isSelected = activeRole === r.id;
            return (
              <button
                key={r.id}
                onClick={() => {
                  onSelectRole(r.id, r.targetTab);
                  onClose();
                }}
                className={`p-3.5 rounded-xl border text-left transition-all flex items-start gap-3.5 group relative ${
                  isSelected
                    ? 'border-[#0051d5] bg-[#eff4ff] shadow-sm'
                    : 'border-[#e5eeff] hover:border-[#b6c6f1] bg-[#ffffff] hover:bg-[#f8f9ff]'
                }`}
              >
                <img
                  src={r.avatar}
                  alt={r.title}
                  className="w-11 h-11 rounded-full object-cover border border-[#b6c6f1] shrink-0 mt-0.5"
                />
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 flex-wrap mb-1">
                    <span className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
                      {r.title}
                    </span>
                    <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full font-['JetBrains_Mono'] ${r.badgeColor}`}>
                      {r.badge}
                    </span>
                  </div>
                  <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e] leading-snug">
                    {r.subtitle}
                  </p>
                </div>
                <div className="shrink-0 pt-1">
                  {isSelected ? (
                    <div className="w-6 h-6 rounded-full bg-[#0051d5] text-white flex items-center justify-center">
                      <Check className="w-3.5 h-3.5" />
                    </div>
                  ) : (
                    <div className="w-6 h-6 rounded-full bg-[#e5eeff] text-[#75777f] flex items-center justify-center group-hover:bg-[#0051d5] group-hover:text-white transition-colors">
                      <ArrowRight className="w-3.5 h-3.5" />
                    </div>
                  )}
                </div>
              </button>
            );
          })}
        </div>

        <div className="bg-[#f8f9ff] p-3 rounded-xl border border-[#e5eeff] text-[12px] text-[#45464e] flex items-center gap-2">
          <span className="w-2 h-2 rounded-full bg-[#009c6b] animate-pulse"></span>
          <span>Semua data dan transaksi disinkronkan secara lokal secara interaktif.</span>
        </div>
      </div>
    </div>
  );
};
