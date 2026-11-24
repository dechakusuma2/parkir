import React from 'react';
import { NavTab, UserRole } from '../types';
import { ASSETS } from '../data/mockData';
import { ChevronDown, Shield, UserCheck } from 'lucide-react';

interface HeaderProps {
  currentTab: NavTab;
  activeRole: UserRole;
  onOpenRoleModal: () => void;
  onSwitchRole: (role: UserRole, targetTab?: NavTab) => void;
}

/** Menampilkan merek, judul tab, dan kontrol untuk mengganti peran pengguna. */
export const Header: React.FC<HeaderProps> = ({
  currentTab,
  activeRole,
  onOpenRoleModal,
}) => {
  // Mengubah tab aktif menjadi subjudul yang dapat dibaca pengguna.
  const getSubTitle = () => {
    switch (currentTab) {
      case 'overview':
        return 'Overview';
      case 'pos-petugas':
        return 'Pos Petugas';
      case 'admin-master':
        return 'Admin Master';
      case 'owner-rekap':
        return 'Owner Rekap';
      default:
        return 'Overview';
    }
  };

  // Mengubah peran aktif menjadi label badge pada tombol profil.
  const getRoleBadge = () => {
    switch (activeRole) {
      case 'petugas':
        return 'PETUGAS';
      case 'admin':
        return 'ADMIN';
      case 'owner':
        return 'OWNER';
      default:
        return 'ROLE';
    }
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-40 bg-[#f8f9ff]/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(15,32,66,0.06)] border-b border-[#e5eeff]">
      <div className="max-w-md mx-auto h-16 px-4 flex items-center justify-between">
        {/* Brand Logo & App Title */}
        <div className="flex items-center gap-2.5">
          <img
            src={ASSETS.logo}
            alt="SmartParkir Logo"
            className="h-8 w-auto object-contain"
          />
          <div className="flex flex-col">
            <span className="font-['Space_Grotesk'] text-[19px] font-bold text-[#000922] tracking-tight leading-none">
              Smart<span className="text-[#0051d5]">Parkir</span>
            </span>
            <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f] uppercase tracking-wider font-semibold">
              {getSubTitle()}
            </span>
          </div>
        </div>

        {/* User Role Switcher Button */}
        <div className="flex items-center gap-2">
          <button
            onClick={onOpenRoleModal}
            className="min-h-[44px] px-2.5 py-1.5 flex items-center gap-2 rounded-xl bg-[#eff4ff] hover:bg-[#e5eeff] text-[#0b1c30] transition-all border border-[#d3e4fe] shadow-xs active:scale-95"
            title="Ganti Hak Akses / Profil"
          >
            <span className="font-['JetBrains_Mono'] text-[10px] px-1.5 py-0.5 rounded bg-[#0f2042] text-white font-bold tracking-wide">
              {getRoleBadge()}
            </span>
            <img
              src={activeRole === 'owner' ? ASSETS.owner : ASSETS.avatar}
              alt="Profile"
              className="w-7 h-7 rounded-full object-cover border border-[#b6c6f1]"
            />
            <ChevronDown className="w-3.5 h-3.5 text-[#75777f]" />
          </button>
        </div>
      </div>
    </header>
  );
};
