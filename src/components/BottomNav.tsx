import React from 'react';
import { NavTab } from '../types';
import { LayoutGrid, ParkingSquare, Database, BarChart3 } from 'lucide-react';

interface BottomNavProps {
  currentTab: NavTab;
  onTabChange: (tab: NavTab) => void;
}

export const BottomNav: React.FC<BottomNavProps> = ({ currentTab, onTabChange }) => {
  const tabs = [
    {
      id: 'overview' as NavTab,
      label: 'Overview',
      icon: LayoutGrid,
    },
    {
      id: 'pos-petugas' as NavTab,
      label: 'POS Kasir',
      icon: ParkingSquare,
    },
    {
      id: 'admin-master' as NavTab,
      label: 'Master',
      icon: Database,
    },
    {
      id: 'owner-rekap' as NavTab,
      label: 'Laporan',
      icon: BarChart3,
    },
  ];

  return (
    <nav className="fixed bottom-0 left-0 right-0 z-40 bg-[#f8f9ff]/90 backdrop-blur-xl shadow-[0_-1px_12px_rgba(15,32,66,0.08)] border-t border-[#e5eeff] pb-[env(safe-area-inset-bottom,0px)]">
      <div className="max-w-md mx-auto h-16 px-2 grid grid-cols-4 items-center">
        {tabs.map((tab) => {
          const Icon = tab.icon;
          const isActive = currentTab === tab.id;

          return (
            <button
              key={tab.id}
              onClick={() => onTabChange(tab.id)}
              className={`flex flex-col items-center justify-center min-h-[44px] gap-1 transition-all rounded-xl py-1 ${
                isActive
                  ? 'text-[#0051d5] font-semibold bg-[#eff4ff]'
                  : 'text-[#45464e] hover:text-[#0b1c30] hover:bg-[#f0f4ff]/50'
              }`}
            >
              <Icon className={`w-5 h-5 transition-transform ${isActive ? 'scale-110' : ''}`} />
              <span className="font-['JetBrains_Mono'] text-[11px] leading-none">
                {tab.label}
              </span>
            </button>
          );
        })}
      </div>
    </nav>
  );
};
