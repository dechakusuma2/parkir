import React, { useState, useEffect } from 'react';
import { NavTab, UserRole } from './types';
import { Header } from './components/Header';
import { BottomNav } from './components/BottomNav';
import { RoleSwitcherModal } from './components/RoleSwitcherModal';
import { OverviewView } from './views/OverviewView';
import { PosKasirView } from './views/PosKasirView';
import { AdminMasterView } from './views/AdminMasterView';
import { OwnerRekapView } from './views/OwnerRekapView';
import { CheckCircle2, Info, AlertTriangle, X } from 'lucide-react';

export default function App() {
  const [currentTab, setCurrentTab] = useState<NavTab>('overview');
  const [currentRole, setCurrentRole] = useState<UserRole>('petugas');
  const [isRoleModalOpen, setIsRoleModalOpen] = useState(false);
  const [toast, setToast] = useState<{
    id: number;
    message: string;
    type: 'success' | 'info' | 'error';
  } | null>(null);

  const showToast = (message: string, type: 'success' | 'info' | 'error' = 'info') => {
    const id = Date.now();
    setToast({ id, message, type });
  };

  useEffect(() => {
    if (toast) {
      const timer = setTimeout(() => {
        setToast(null);
      }, 3500);
      return () => clearTimeout(timer);
    }
  }, [toast]);

  const handleSelectRole = (role: UserRole) => {
    setCurrentRole(role);
    if (role === 'petugas') {
      setCurrentTab('pos-petugas');
    } else if (role === 'admin') {
      setCurrentTab('admin-master');
    } else if (role === 'owner') {
      setCurrentTab('owner-rekap');
    }
    showToast(`Beralih ke mode ${role.toUpperCase()}`, 'success');
  };

  const handleLoginRole = (role: UserRole, targetTab: NavTab) => {
    setCurrentRole(role);
    setCurrentTab(targetTab);
  };

  const handleLogout = () => {
    setCurrentTab('overview');
    showToast('Sesi ditutup, kembali ke portal utama', 'info');
  };

  return (
    <div className="min-h-screen bg-[#dce9ff]/40 text-[#0b1c30] flex flex-col items-center justify-start antialiased selection:bg-[#0051d5] selection:text-white">
      {/* Centered Mobile/Tablet Viewport Shell */}
      <div className="w-full max-w-[480px] min-h-screen bg-[#eff4ff] flex flex-col shadow-2xl relative border-x border-[#d3e4fe]">
        {/* Top Header */}
        <Header
          currentRole={currentRole}
          onOpenRoleModal={() => setIsRoleModalOpen(true)}
        />

        {/* Main Content Area with top and bottom clearance for sticky bars */}
        <main className="flex-1 w-full px-3.5 pt-16 pb-24 overflow-y-auto">
          {currentTab === 'overview' && (
            <OverviewView
              onLoginRole={handleLoginRole}
              onShowToast={showToast}
            />
          )}

          {currentTab === 'pos-petugas' && (
            <PosKasirView
              onShowToast={showToast}
              onLogout={handleLogout}
            />
          )}

          {currentTab === 'admin-master' && (
            <AdminMasterView
              onShowToast={showToast}
              onLogout={handleLogout}
            />
          )}

          {currentTab === 'owner-rekap' && (
            <OwnerRekapView
              onShowToast={showToast}
              onLogout={handleLogout}
            />
          )}
        </main>

        {/* Fixed Bottom Navigation */}
        <BottomNav
          currentTab={currentTab}
          onSelectTab={setCurrentTab}
          activeRole={currentRole}
          onOpenRoleModal={() => setIsRoleModalOpen(true)}
        />

        {/* Role Switcher Modal */}
        <RoleSwitcherModal
          isOpen={isRoleModalOpen}
          onClose={() => setIsRoleModalOpen(false)}
          currentRole={currentRole}
          onSelectRole={handleSelectRole}
        />

        {/* Toast Notification Floating Banner */}
        {toast && (
          <div className="fixed bottom-20 left-1/2 -translate-x-1/2 z-50 w-11/12 max-w-[420px] transition-all animate-in fade-in slide-in-from-bottom-3 duration-200">
            <div
              className={`p-3 rounded-2xl shadow-xl flex items-center justify-between gap-2 border ${
                toast.type === 'success'
                  ? 'bg-[#002718] text-[#6ffbbe] border-[#009c6b]'
                  : toast.type === 'error'
                  ? 'bg-[#410002] text-[#ffdad6] border-[#ba1a1a]'
                  : 'bg-[#000922] text-white border-[#316bf3]'
              }`}
            >
              <div className="flex items-center gap-2.5 min-w-0">
                {toast.type === 'success' ? (
                  <CheckCircle2 className="w-5 h-5 text-[#6ffbbe] shrink-0" />
                ) : toast.type === 'error' ? (
                  <AlertTriangle className="w-5 h-5 text-[#ffdad6] shrink-0" />
                ) : (
                  <Info className="w-5 h-5 text-[#316bf3] shrink-0" />
                )}
                <span className="font-['Hanken_Grotesk'] text-[13px] font-medium leading-snug">
                  {toast.message}
                </span>
              </div>
              <button
                onClick={() => setToast(null)}
                className="w-6 h-6 rounded-md hover:bg-white/10 flex items-center justify-center shrink-0"
              >
                <X className="w-4 h-4 opacity-70" />
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
