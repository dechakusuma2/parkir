import React, { useState } from 'react';
import {
  VehicleTariff,
  ParkingArea,
  VehicleCategory,
  StaffUser,
  AuditLogItem,
} from '../types';
import {
  INITIAL_TARIFFS,
  INITIAL_AREAS,
  INITIAL_CATEGORIES,
  INITIAL_USERS,
  INITIAL_AUDIT_LOGS,
} from '../data/mockData';
import {
  ShieldAlert,
  ShieldCheck,
  RotateCw,
  LogOut,
  ParkingSquare,
  Users,
  CreditCard,
  Layers,
  Car,
  Bike,
  Truck,
  Edit2,
  Plus,
  Search,
  Check,
  X,
  Radio,
  FileSpreadsheet,
  Download,
  AlertTriangle,
  FileText,
  BadgeAlert,
  ChevronRight,
  MoreVertical,
  Activity,
  UserPlus,
} from 'lucide-react';
import { DenahModal } from '../components/DenahModal';

interface AdminMasterViewProps {
  onShowToast: (message: string, type?: 'success' | 'info' | 'error') => void;
  onLogout: () => void;
}

export const AdminMasterView: React.FC<AdminMasterViewProps> = ({
  onShowToast,
  onLogout,
}) => {
  const [activeSubTab, setActiveSubTab] = useState<
    'tarif' | 'area' | 'kategori' | 'user' | 'audit'
  >('tarif');

  // Data states
  const [tariffs, setTariffs] = useState<VehicleTariff[]>(INITIAL_TARIFFS);
  const [areas, setAreas] = useState<ParkingArea[]>(INITIAL_AREAS);
  const [categories, setCategories] = useState<VehicleCategory[]>(INITIAL_CATEGORIES);
  const [users, setUsers] = useState<StaffUser[]>(INITIAL_USERS);
  const [auditLogs, setAuditLogs] = useState<AuditLogItem[]>(INITIAL_AUDIT_LOGS);

  // Modals
  const [editingTariff, setEditingTariff] = useState<VehicleTariff | null>(null);
  const [isAreaModalOpen, setIsAreaModalOpen] = useState(false);
  const [isUserModalOpen, setIsUserModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<StaffUser | null>(null);
  const [selectedDenahArea, setSelectedDenahArea] = useState<ParkingArea | null>(null);

  // In-card quick edit for motor
  const [isMotorEditing, setIsMotorEditing] = useState(false);
  const [motorJam1, setMotorJam1] = useState(2000);
  const [motorJamNext, setMotorJamNext] = useState(1000);
  const [motorDenda, setMotorDenda] = useState(25000);

  // User search & filter
  const [userQuery, setUserQuery] = useState('');
  const [userRoleFilter, setUserRoleFilter] = useState('ALL');

  // Audit filter & search
  const [auditFilter, setAuditFilter] = useState<string>('all');
  const [auditSearch, setAuditSearch] = useState('');

  // Form states
  const [newAreaName, setNewAreaName] = useState('');
  const [newAreaType, setNewAreaType] = useState('Mobil (Roda 4)');
  const [newAreaCapacity, setNewAreaCapacity] = useState('100');

  const [userName, setUserName] = useState('');
  const [userUsername, setUserUsername] = useState('');
  const [userRole, setUserRole] = useState<'Petugas' | 'Admin' | 'Owner'>('Petugas');
  const [userUnit, setUserUnit] = useState('POS Gate Utama');

  // Handlers
  const handleSaveQuickMotor = () => {
    setTariffs((prev) =>
      prev.map((t) =>
        t.type === 'motor'
          ? {
              ...t,
              firstHourRate: motorJam1,
              nextHourRate: motorJamNext,
              lostTicketFee: motorDenda,
            }
          : t
      )
    );
    setIsMotorEditing(false);
    onShowToast('Tarif Sepeda Motor berhasil diperbarui!', 'success');
  };

  const handleOpenEditTariff = (t: VehicleTariff) => {
    setEditingTariff({ ...t });
  };

  const handleSaveModalTariff = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingTariff) return;
    setTariffs((prev) =>
      prev.map((t) => (t.id === editingTariff.id ? editingTariff : t))
    );
    setEditingTariff(null);
    onShowToast(`Tarif ${editingTariff.categoryName} berhasil disimpan`, 'success');
  };

  const handleAddArea = (e: React.FormEvent) => {
    e.preventDefault();
    const capacityNum = parseInt(newAreaCapacity, 10) || 100;
    const newArea: ParkingArea = {
      id: `area-${Date.now()}`,
      name: newAreaName || 'Area Parkir Baru',
      code: newAreaName.slice(0, 2).toUpperCase() || 'C',
      type: newAreaType,
      totalSlots: capacityNum,
      occupiedSlots: 0,
      statusBadge: 'Tersedia',
      badgeType: 'success',
      iotType: 'Sensor IoT: Aktif (Ultrasonic Nodes)',
      iotNodes: 16,
    };
    setAreas((prev) => [...prev, newArea]);
    setIsAreaModalOpen(false);
    setNewAreaName('');
    onShowToast(`Area ${newArea.name} berhasil didaftarkan`, 'success');
  };

  const handleToggleUserStatus = (id: string) => {
    setUsers((prev) =>
      prev.map((u) => {
        if (u.id === id) {
          const newStatus = u.status === 'Aktif' ? 'Nonaktif' : 'Aktif';
          onShowToast(`Status akun ${u.name} diubah menjadi ${newStatus}`, 'info');
          return { ...u, status: newStatus };
        }
        return u;
      })
    );
  };

  const handleOpenUserModal = (u?: StaffUser) => {
    if (u) {
      setEditingUser(u);
      setUserName(u.name);
      setUserUsername(u.username);
      setUserRole(u.role);
      setUserUnit(u.unit);
    } else {
      setEditingUser(null);
      setUserName('');
      setUserUsername('');
      setUserRole('Petugas');
      setUserUnit('POS Gate Utama');
    }
    setIsUserModalOpen(true);
  };

  const handleSaveUser = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingUser) {
      setUsers((prev) =>
        prev.map((u) =>
          u.id === editingUser.id
            ? {
                ...u,
                name: userName,
                username: userUsername,
                role: userRole,
                unit: userUnit,
              }
            : u
        )
      );
      onShowToast(`Data pengguna ${userName} berhasil diperbarui`, 'success');
    } else {
      const newUser: StaffUser = {
        id: `usr-${Date.now()}`,
        name: userName || 'Pengguna Baru',
        username: userUsername || `user_${Date.now().toString().slice(-4)}`,
        role: userRole,
        unit: userUnit,
        status: 'Aktif',
        lastLogin: 'Belum pernah login',
        initials: (userName || 'PB')
          .split(' ')
          .map((n) => n[0])
          .join('')
          .toUpperCase()
          .slice(0, 2),
      };
      setUsers((prev) => [newUser, ...prev]);
      onShowToast(`Pengguna baru ${newUser.name} berhasil ditambahkan`, 'success');
    }
    setIsUserModalOpen(false);
  };

  const filteredUsers = users.filter((u) => {
    const q = userQuery.toLowerCase();
    const matchesQuery =
      u.name.toLowerCase().includes(q) ||
      u.username.toLowerCase().includes(q) ||
      u.unit.toLowerCase().includes(q);
    const matchesRole = userRoleFilter === 'ALL' || u.role === userRoleFilter;
    return matchesQuery && matchesRole;
  });

  const filteredAuditLogs = auditLogs.filter((log) => {
    const q = auditSearch.toLowerCase();
    const matchesQuery =
      !q ||
      log.actionTitle.toLowerCase().includes(q) ||
      (log.vehiclePlate && log.vehiclePlate.toLowerCase().includes(q)) ||
      (log.authorizer && log.authorizer.toLowerCase().includes(q)) ||
      (log.cashierName && log.cashierName.toLowerCase().includes(q));
    const matchesFilter = auditFilter === 'all' || log.category === auditFilter;
    return matchesQuery && matchesFilter;
  });

  return (
    <div className="flex flex-col w-full space-y-3.5 pb-4">
      {/* Info Administrator Bar */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-2xl bg-[#000922] text-white shadow-sm gap-3 border border-[#1b3464]">
        <div className="flex items-center gap-3 min-w-0">
          <div className="w-10 h-10 rounded-xl bg-[#316bf3] text-white flex items-center justify-center shrink-0 shadow-sm">
            <ShieldCheck className="w-5 h-5" />
          </div>
          <div className="flex flex-col min-w-0">
            <div className="flex items-center gap-2 flex-wrap">
              <span className="font-['Space_Grotesk'] text-[15px] font-bold truncate text-white">
                Hendra Wijaya
              </span>
              <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full bg-[#0051d5] text-white font-bold uppercase tracking-wider">
                Super Admin
              </span>
            </div>
            <span className="font-['Hanken_Grotesk'] text-[12px] text-[#7988b0]">
              ID: ADM-9402 • Shift Pagi Ops Center
            </span>
          </div>
        </div>

        <div className="flex items-center gap-2 shrink-0 self-end sm:self-auto">
          <button
            onClick={() => onShowToast('Sesi Master & IoT Gateway tersinkronisasi', 'success')}
            className="min-h-[40px] px-3 flex items-center gap-1.5 rounded-xl bg-[#0f2042] hover:bg-[#1f3768] text-[#d3e4fe] transition-colors font-['JetBrains_Mono'] text-[11px] font-semibold border border-[#37466a]"
          >
            <RotateCw className="w-3.5 h-3.5" />
            <span>Sinkron</span>
          </button>
          <button
            onClick={() => {
              if (window.confirm('Keluar dari sesi Administrator Master?')) {
                onShowToast('Berhasil keluar dari konsol admin', 'info');
                onLogout();
              }
            }}
            className="min-h-[40px] px-3 flex items-center gap-1.5 rounded-xl bg-[#ffdad6]/20 hover:bg-[#ffdad6]/30 text-[#ffdad6] transition-colors font-['JetBrains_Mono'] text-[11px] font-semibold"
          >
            <LogOut className="w-3.5 h-3.5" />
            <span>Logout</span>
          </button>
        </div>
      </div>

      {/* Metric Overview Cards */}
      <div className="grid grid-cols-2 gap-2">
        <div className="p-3.5 rounded-2xl bg-white shadow-sm flex flex-col justify-between border border-[#e5eeff]">
          <div className="flex items-center justify-between text-[#75777f]">
            <span className="font-['JetBrains_Mono'] text-[10px] uppercase font-bold tracking-wider">
              Total Kapasitas
            </span>
            <ParkingSquare className="w-4 h-4 text-[#0051d5]" />
          </div>
          <div className="mt-2 flex items-baseline gap-1">
            <span className="font-['Space_Grotesk'] text-[24px] font-bold text-[#000922]">
              385
            </span>
            <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">Slot</span>
          </div>
          <span className="font-['JetBrains_Mono'] text-[10px] text-[#009c6b] mt-1 flex items-center gap-1 font-semibold">
            <span className="w-2 h-2 rounded-full bg-[#009c6b] inline-block animate-pulse"></span>
            3 Area Terhubung IoT
          </span>
        </div>

        <div className="p-3.5 rounded-2xl bg-white shadow-sm flex flex-col justify-between border border-[#e5eeff]">
          <div className="flex items-center justify-between text-[#75777f]">
            <span className="font-['JetBrains_Mono'] text-[10px] uppercase font-bold tracking-wider">
              Pengguna Aktif
            </span>
            <Users className="w-4 h-4 text-[#0051d5]" />
          </div>
          <div className="mt-2 flex items-baseline gap-1">
            <span className="font-['Space_Grotesk'] text-[24px] font-bold text-[#000922]">
              12
            </span>
            <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">Personel</span>
          </div>
          <span className="font-['JetBrains_Mono'] text-[10px] text-[#0051d5] mt-1 font-semibold">
            4 POS Kasir On-duty
          </span>
        </div>
      </div>

      {/* Segmented Tab Bar */}
      <div className="flex w-full overflow-x-auto p-1 rounded-2xl bg-[#dce9ff] gap-1 shadow-inner border border-[#d3e4fe] no-scrollbar">
        {[
          { id: 'tarif' as const, label: 'Tarif Parkir', icon: CreditCard },
          { id: 'area' as const, label: 'Area Parkir', icon: Layers },
          { id: 'kategori' as const, label: 'Kategori', icon: Car },
          { id: 'user' as const, label: 'Kelola User', icon: Users },
          { id: 'audit' as const, label: 'Audit Log', icon: Activity },
        ].map((tab) => {
          const Icon = tab.icon;
          const isActive = activeSubTab === tab.id;

          return (
            <button
              key={tab.id}
              onClick={() => setActiveSubTab(tab.id)}
              className={`flex-1 min-h-[42px] px-2.5 py-1.5 rounded-xl text-center flex items-center justify-center gap-1.5 transition-all text-nowrap ${
                isActive
                  ? 'bg-white text-[#000922] shadow-sm font-bold'
                  : 'text-[#45464e] hover:text-[#0b1c30]'
              }`}
            >
              <Icon className="w-4 h-4 text-[#0051d5]" />
              <span className="font-['JetBrains_Mono'] text-[11px]">{tab.label}</span>
            </button>
          );
        })}
      </div>

      {/* SUB-PANEL 1: TARIF PARKIR */}
      {activeSubTab === 'tarif' && (
        <section className="flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <CreditCard className="w-5 h-5 text-[#0051d5]" />
              <h2 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Aturan Tarif Parkir
              </h2>
            </div>
            <button
              onClick={() =>
                handleOpenEditTariff({
                  id: `tar-${Date.now()}`,
                  categoryName: 'Skema Khusus Baru',
                  type: 'mobil',
                  tag: 'Promo',
                  description: 'Tarif khusus acara atau akhir pekan',
                  firstHourRate: 4000,
                  nextHourRate: 2000,
                  lostTicketFee: 25000,
                })
              }
              className="min-h-[38px] px-3 rounded-xl bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0051d5] flex items-center gap-1 border border-[#d3e4fe] font-['JetBrains_Mono'] text-[11px] font-bold"
            >
              <Plus className="w-3.5 h-3.5" />
              <span>Skema Baru</span>
            </button>
          </div>

          {/* Tariffs List */}
          <div className="space-y-3">
            {/* Sepeda Motor */}
            <div className="p-4 rounded-2xl bg-white shadow-sm flex flex-col gap-2.5 border border-[#e5eeff]">
              <div className="flex items-start justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl bg-[#eff4ff] flex items-center justify-center text-[#0051d5] border border-[#d3e4fe]">
                    <Bike className="w-5 h-5" />
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
                        Sepeda Motor
                      </h3>
                      <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full bg-[#eff4ff] text-[#45464e] border border-[#d3e4fe]">
                        Reguler
                      </span>
                    </div>
                    <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                      Kendaraan roda 2, scooter, moped
                    </p>
                  </div>
                </div>
                <button
                  onClick={() => setIsMotorEditing(!isMotorEditing)}
                  className="min-h-[36px] px-3 rounded-lg bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0051d5] flex items-center gap-1 font-['JetBrains_Mono'] text-[11px] font-bold"
                >
                  <Edit2 className="w-3.5 h-3.5" />
                  <span>{isMotorEditing ? 'Tutup' : 'Edit'}</span>
                </button>
              </div>

              <div className="grid grid-cols-3 gap-2 p-2.5 rounded-xl bg-[#eff4ff] border border-[#d3e4fe] font-['JetBrains_Mono']">
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">1 Jam Pertama</span>
                  <span className="text-[13px] font-bold text-[#000922]">
                    Rp {motorJam1.toLocaleString('id-ID')}
                  </span>
                  <span className="text-[10px] text-[#75777f]">Flat</span>
                </div>
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">Jam Berikutnya</span>
                  <span className="text-[13px] font-bold text-[#000922]">
                    Rp {motorJamNext.toLocaleString('id-ID')}
                  </span>
                  <span className="text-[10px] text-[#75777f]">/ jam</span>
                </div>
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#ba1a1a]">Tiket Hilang</span>
                  <span className="text-[13px] font-bold text-[#ba1a1a]">
                    Rp {motorDenda.toLocaleString('id-ID')}
                  </span>
                  <span className="text-[10px] text-[#75777f]">Denda</span>
                </div>
              </div>

              {/* In-Card Quick Form */}
              {isMotorEditing && (
                <div className="flex flex-col gap-2.5 p-3 rounded-xl bg-[#dce9ff]/60 border border-[#b6c6f1] animate-in fade-in">
                  <div className="grid grid-cols-3 gap-2">
                    <div>
                      <label className="text-[10px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                        Tarif Jam 1
                      </label>
                      <input
                        type="number"
                        value={motorJam1}
                        onChange={(e) => setMotorJam1(Number(e.target.value))}
                        className="w-full mt-1 p-2 bg-white rounded-lg text-[13px] font-['JetBrains_Mono'] font-bold border border-[#b6c6f1]"
                      />
                    </div>
                    <div>
                      <label className="text-[10px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                        Jam Lanjut
                      </label>
                      <input
                        type="number"
                        value={motorJamNext}
                        onChange={(e) => setMotorJamNext(Number(e.target.value))}
                        className="w-full mt-1 p-2 bg-white rounded-lg text-[13px] font-['JetBrains_Mono'] font-bold border border-[#b6c6f1]"
                      />
                    </div>
                    <div>
                      <label className="text-[10px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                        Denda Hilang
                      </label>
                      <input
                        type="number"
                        value={motorDenda}
                        onChange={(e) => setMotorDenda(Number(e.target.value))}
                        className="w-full mt-1 p-2 bg-white rounded-lg text-[13px] font-['JetBrains_Mono'] font-bold border border-[#b6c6f1]"
                      />
                    </div>
                  </div>
                  <div className="flex justify-end gap-2 pt-1">
                    <button
                      onClick={() => setIsMotorEditing(false)}
                      className="px-3 py-1.5 rounded-lg bg-white text-[#45464e] text-xs font-semibold"
                    >
                      Batal
                    </button>
                    <button
                      onClick={handleSaveQuickMotor}
                      className="px-3 py-1.5 rounded-lg bg-[#000922] text-white text-xs font-semibold flex items-center gap-1"
                    >
                      <Check className="w-3 h-3" />
                      Simpan Perubahan
                    </button>
                  </div>
                </div>
              )}
            </div>

            {/* Mobil Penumpang */}
            <div className="p-4 rounded-2xl bg-white shadow-sm flex flex-col gap-2.5 border border-[#e5eeff]">
              <div className="flex items-start justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl bg-[#eff4ff] flex items-center justify-center text-[#0051d5] border border-[#d3e4fe]">
                    <Car className="w-5 h-5" />
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
                        Mobil Penumpang
                      </h3>
                      <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full bg-[#316bf3]/15 text-[#0051d5] font-bold">
                        Terlaris
                      </span>
                    </div>
                    <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                      Sedan, SUV, MPV, City Car
                    </p>
                  </div>
                </div>
                <button
                  onClick={() =>
                    handleOpenEditTariff(tariffs.find((t) => t.type === 'mobil') || tariffs[1])
                  }
                  className="min-h-[36px] px-3 rounded-lg bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0051d5] flex items-center gap-1 font-['JetBrains_Mono'] text-[11px] font-bold"
                >
                  <Edit2 className="w-3.5 h-3.5" />
                  <span>Edit</span>
                </button>
              </div>

              <div className="grid grid-cols-3 gap-2 p-2.5 rounded-xl bg-[#eff4ff] border border-[#d3e4fe] font-['JetBrains_Mono']">
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">1 Jam Pertama</span>
                  <span className="text-[13px] font-bold text-[#000922]">Rp 5.000</span>
                  <span className="text-[10px] text-[#75777f]">Flat</span>
                </div>
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">Jam Berikutnya</span>
                  <span className="text-[13px] font-bold text-[#000922]">Rp 3.000</span>
                  <span className="text-[10px] text-[#75777f]">/ jam</span>
                </div>
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#0051d5]">Maks. Sehari</span>
                  <span className="text-[13px] font-bold text-[#0051d5]">Rp 50.000</span>
                  <span className="text-[10px] text-[#75777f]">24 Jam</span>
                </div>
              </div>
            </div>

            {/* Truk / Bus */}
            <div className="p-4 rounded-2xl bg-white shadow-sm flex flex-col gap-2.5 border border-[#e5eeff]">
              <div className="flex items-start justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl bg-[#eff4ff] flex items-center justify-center text-[#0051d5] border border-[#d3e4fe]">
                    <Truck className="w-5 h-5" />
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
                        Truk / Bus
                      </h3>
                      <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full bg-[#eff4ff] text-[#45464e] border border-[#d3e4fe]">
                        Heavy
                      </span>
                    </div>
                    <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                      Kendaraan muatan &amp; bus pariwisata
                    </p>
                  </div>
                </div>
                <button
                  onClick={() =>
                    handleOpenEditTariff(tariffs.find((t) => t.type === 'truk') || tariffs[2])
                  }
                  className="min-h-[36px] px-3 rounded-lg bg-[#eff4ff] hover:bg-[#dce9ff] text-[#0051d5] flex items-center gap-1 font-['JetBrains_Mono'] text-[11px] font-bold"
                >
                  <Edit2 className="w-3.5 h-3.5" />
                  <span>Edit</span>
                </button>
              </div>

              <div className="grid grid-cols-2 gap-2 p-2.5 rounded-xl bg-[#eff4ff] border border-[#d3e4fe] font-['JetBrains_Mono']">
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">Tarif Flat Hourly</span>
                  <span className="text-[13px] font-bold text-[#000922]">Rp 10.000</span>
                  <span className="text-[10px] text-[#75777f]">Per Jam Berlaku Kumulatif</span>
                </div>
                <div className="flex flex-col">
                  <span className="text-[10px] text-[#75777f]">Area Diizinkan</span>
                  <span className="text-[13px] font-bold text-[#000922]">Outdoor B</span>
                  <span className="text-[10px] text-[#75777f]">Gate Khusus</span>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* SUB-PANEL 2: AREA PARKIR */}
      {activeSubTab === 'area' && (
        <section className="flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <Layers className="w-5 h-5 text-[#0051d5]" />
              <h2 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Kapasitas &amp; Zona Parkir
              </h2>
            </div>
            <button
              onClick={() => setIsAreaModalOpen(true)}
              className="min-h-[38px] px-3 rounded-xl bg-[#000922] text-white flex items-center gap-1 shadow-sm font-['JetBrains_Mono'] text-[11px] font-bold"
            >
              <Plus className="w-3.5 h-3.5" />
              <span>Tambah Area</span>
            </button>
          </div>

          <div className="space-y-3">
            {areas.map((area) => {
              const occupancyPct = Math.round((area.occupiedSlots / area.totalSlots) * 100);
              const remaining = area.totalSlots - area.occupiedSlots;

              return (
                <div
                  key={area.id}
                  className="p-4 rounded-2xl bg-white shadow-sm flex flex-col gap-3 border border-[#e5eeff]"
                >
                  <div className="flex items-start justify-between gap-2">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 rounded-xl bg-[#eff4ff] text-[#0051d5] flex items-center justify-center font-['Space_Grotesk'] text-lg font-bold shrink-0 border border-[#d3e4fe]">
                        {area.code}
                      </div>
                      <div>
                        <h3 className="font-['Space_Grotesk'] text-[15px] font-bold text-[#000922]">
                          {area.name}
                        </h3>
                        <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                          {area.type}
                        </p>
                      </div>
                    </div>
                    <span
                      className={`font-['JetBrains_Mono'] text-[11px] px-2.5 py-1 rounded-full font-bold flex items-center gap-1 ${
                        area.badgeType === 'error'
                          ? 'bg-[#ffdad6] text-[#93000a]'
                          : area.badgeType === 'secondary'
                          ? 'bg-[#eff4ff] text-[#0051d5]'
                          : 'bg-[#d3e4fe] text-[#009c6b]'
                      }`}
                    >
                      <span
                        className={`w-1.5 h-1.5 rounded-full ${
                          area.badgeType === 'error'
                            ? 'bg-[#ba1a1a]'
                            : area.badgeType === 'secondary'
                            ? 'bg-[#0051d5]'
                            : 'bg-[#009c6b]'
                        }`}
                      ></span>
                      {area.statusBadge}
                    </span>
                  </div>

                  {/* Progress Bar */}
                  <div className="flex flex-col gap-1">
                    <div className="flex justify-between font-['JetBrains_Mono'] text-[11px]">
                      <span className="text-[#45464e]">
                        Terisi: {area.occupiedSlots} / {area.totalSlots}
                      </span>
                      <span className="font-bold text-[#000922]">
                        Sisa {remaining} Slot
                      </span>
                    </div>
                    <div className="w-full h-3 rounded-full bg-[#dce9ff] overflow-hidden">
                      <div
                        className={`h-full rounded-full transition-all duration-700 ${
                          area.badgeType === 'error'
                            ? 'bg-[#ba1a1a]'
                            : area.badgeType === 'secondary'
                            ? 'bg-[#0051d5]'
                            : 'bg-[#009c6b]'
                        }`}
                        style={{ width: `${occupancyPct}%` }}
                      ></div>
                    </div>
                  </div>

                  <div className="flex items-center justify-between pt-1 text-[#45464e] border-t border-[#e5eeff]">
                    <div className="flex items-center gap-1.5">
                      <span className="w-2 h-2 rounded-full bg-[#009c6b] animate-ping"></span>
                      <span className="font-['JetBrains_Mono'] text-[11px] font-semibold">
                        {area.iotType}
                      </span>
                    </div>
                    <button
                      onClick={() => setSelectedDenahArea(area)}
                      className="px-2.5 py-1 rounded-lg text-[#0051d5] hover:bg-[#eff4ff] font-['JetBrains_Mono'] text-[11px] font-bold flex items-center gap-1"
                    >
                      <span>Lihat Denah</span>
                      <ChevronRight className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        </section>
      )}

      {/* SUB-PANEL 3: KATEGORI KENDARAAN */}
      {activeSubTab === 'kategori' && (
        <section className="flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <Car className="w-5 h-5 text-[#0051d5]" />
              <h2 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Klasifikasi Kendaraan
              </h2>
            </div>
            <button
              onClick={() => onShowToast('Formulir kategori baru dibuka', 'info')}
              className="min-h-[38px] px-3 rounded-xl bg-[#eff4ff] text-[#000922] font-['JetBrains_Mono'] text-[11px] font-bold flex items-center gap-1 border border-[#d3e4fe]"
            >
              <Plus className="w-3.5 h-3.5" />
              <span>Tambah Kategori</span>
            </button>
          </div>

          <div className="grid grid-cols-1 gap-2.5">
            {categories.map((cat) => (
              <div
                key={cat.id}
                className="p-3 rounded-2xl bg-white shadow-sm flex items-center justify-between border border-[#e5eeff]"
              >
                <div className="flex items-center gap-3">
                  <img
                    src={cat.imageUrl}
                    alt={cat.title}
                    className="w-14 h-14 rounded-xl object-cover border border-[#d3e4fe]"
                  />
                  <div className="flex flex-col">
                    <div className="flex items-center gap-2">
                      <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                        {cat.title}
                      </span>
                      <span className="font-['JetBrains_Mono'] text-[10px] px-1.5 py-0.5 rounded bg-[#eff4ff] text-[#0051d5] font-bold border border-[#d3e4fe]">
                        KODE: {cat.code}
                      </span>
                    </div>
                    <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                      {cat.description}
                    </span>
                  </div>
                </div>
                <button
                  onClick={() => onShowToast(`Pengaturan ${cat.title}`, 'info')}
                  className="w-9 h-9 flex items-center justify-center text-[#75777f] hover:text-[#000922]"
                >
                  <MoreVertical className="w-5 h-5" />
                </button>
              </div>
            ))}
          </div>
        </section>
      )}

      {/* SUB-PANEL 4: MANAJEMEN USER */}
      {activeSubTab === 'user' && (
        <section className="flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <Users className="w-5 h-5 text-[#0051d5]" />
              <h2 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Manajemen Akun
              </h2>
            </div>
            <button
              onClick={() => handleOpenUserModal()}
              className="min-h-[38px] px-3 rounded-xl bg-[#000922] text-white flex items-center gap-1.5 shadow-sm font-['JetBrains_Mono'] text-[11px] font-bold"
            >
              <UserPlus className="w-3.5 h-3.5" />
              <span>Tambah User</span>
            </button>
          </div>

          {/* Search & Filter */}
          <div className="flex items-center gap-2 p-1.5 rounded-2xl bg-[#eff4ff] border border-[#d3e4fe]">
            <div className="flex-1 flex items-center gap-2 px-3 bg-white rounded-xl min-h-[42px] border border-[#e5eeff]">
              <Search className="w-4 h-4 text-[#75777f]" />
              <input
                type="text"
                value={userQuery}
                onChange={(e) => setUserQuery(e.target.value)}
                placeholder="Cari nama, username, atau ID..."
                className="w-full bg-transparent text-[#000922] font-['Hanken_Grotesk'] text-[13px] outline-none"
              />
            </div>
            <select
              value={userRoleFilter}
              onChange={(e) => setUserRoleFilter(e.target.value)}
              className="min-h-[42px] px-2.5 rounded-xl bg-white text-[#000922] font-['JetBrains_Mono'] text-[11px] font-bold outline-none border border-[#e5eeff]"
            >
              <option value="ALL">Semua Role</option>
              <option value="Petugas">Petugas</option>
              <option value="Admin">Admin</option>
              <option value="Owner">Owner</option>
            </select>
          </div>

          {/* User Cards List */}
          <div className="flex flex-col gap-2.5">
            {filteredUsers.map((u) => {
              const isInactive = u.status === 'Nonaktif';

              return (
                <div
                  key={u.id}
                  className={`p-3.5 rounded-2xl bg-white shadow-sm flex flex-col gap-2 border border-[#e5eeff] transition-all ${
                    isInactive ? 'opacity-65' : ''
                  }`}
                >
                  <div className="flex items-start justify-between">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 rounded-full bg-[#eff4ff] text-[#0051d5] flex items-center justify-center font-['Space_Grotesk'] text-[13px] font-bold border border-[#d3e4fe]">
                        {u.initials}
                      </div>
                      <div>
                        <div className="flex items-center gap-2 flex-wrap">
                          <span className="font-['Space_Grotesk'] text-[14px] font-bold text-[#000922]">
                            {u.name}
                          </span>
                          <span className="font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full bg-[#eff4ff] text-[#0051d5] font-bold border border-[#d3e4fe]">
                            {u.role}
                          </span>
                        </div>
                        <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                          @{u.username} • {u.unit}
                        </span>
                      </div>
                    </div>
                    <span
                      className={`font-['JetBrains_Mono'] text-[10px] px-2 py-0.5 rounded-full font-bold flex items-center gap-1 ${
                        isInactive
                          ? 'bg-[#eff4ff] text-[#75777f]'
                          : 'bg-[#d3e4fe] text-[#009c6b]'
                      }`}
                    >
                      <span
                        className={`w-1.5 h-1.5 rounded-full ${
                          isInactive ? 'bg-[#75777f]' : 'bg-[#009c6b]'
                        }`}
                      ></span>
                      {u.status}
                    </span>
                  </div>

                  <div className="flex items-center justify-between pt-1 text-[11px] text-[#45464e] border-t border-[#e5eeff]">
                    <span className="font-['JetBrains_Mono'] text-[#75777f]">
                      Login Terakhir: {u.lastLogin}
                    </span>
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => handleOpenUserModal(u)}
                        className="text-[#0051d5] font-['JetBrains_Mono'] font-bold hover:underline"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => handleToggleUserStatus(u.id)}
                        className={`font-['JetBrains_Mono'] font-bold hover:underline ${
                          isInactive ? 'text-[#0051d5]' : 'text-[#ba1a1a]'
                        }`}
                      >
                        {isInactive ? 'Aktifkan' : 'Nonaktifkan'}
                      </button>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </section>
      )}

      {/* SUB-PANEL 5: AUDIT LOG & REKAM JEJAK SISTEM (from Image 11) */}
      {activeSubTab === 'audit' && (
        <section className="flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div className="flex flex-col">
              <h2 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Audit Log &amp; Rekam Jejak Sistem
              </h2>
              <p className="font-['Hanken_Grotesk'] text-[11px] text-[#45464e]">
                Monitoring keamanan transaksi, pintu palang, dan login staf
              </p>
            </div>
            <div className="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#eff4ff] text-[#0051d5] font-['JetBrains_Mono'] text-[10px] font-bold border border-[#d3e4fe]">
              <span className="w-2 h-2 rounded-full bg-[#0051d5] animate-ping"></span>
              <span>LIVE SYNC</span>
            </div>
          </div>

          {/* Telemetry Bento */}
          <div className="grid grid-cols-3 gap-2">
            <div className="p-2.5 rounded-xl bg-white shadow-sm border border-[#e5eeff] flex flex-col">
              <span className="font-['JetBrains_Mono'] text-[9px] text-[#75777f] font-bold">
                EVENT HARI INI
              </span>
              <span className="font-['Space_Grotesk'] text-[18px] font-bold text-[#000922]">
                1.482
              </span>
              <span className="text-[9px] text-[#75777f]">Semua Gate Aktif</span>
            </div>
            <div className="p-2.5 rounded-xl bg-white shadow-sm border border-[#e5eeff] flex flex-col">
              <span className="font-['JetBrains_Mono'] text-[9px] text-[#75777f] font-bold">
                MANUAL OVERRIDE
              </span>
              <span className="font-['Space_Grotesk'] text-[18px] font-bold text-[#0051d5]">
                3 <span className="text-[10px] font-normal text-[#45464e]">insiden</span>
              </span>
              <span className="text-[9px] text-[#75777f]">Perlu Review</span>
            </div>
            <div className="p-2.5 rounded-xl bg-white shadow-sm border border-[#e5eeff] flex flex-col">
              <span className="font-['JetBrains_Mono'] text-[9px] text-[#75777f] font-bold">
                SYSTEM ALERT
              </span>
              <span className="font-['Space_Grotesk'] text-[18px] font-bold text-[#ba1a1a]">
                1 <span className="text-[10px] font-normal text-[#45464e]">tiket</span>
              </span>
              <span className="text-[9px] text-[#75777f]">Tiket Hilang</span>
            </div>
          </div>

          {/* Search bar */}
          <div className="relative w-full">
            <Search className="w-4 h-4 absolute left-3 top-3 text-[#75777f]" />
            <input
              type="text"
              value={auditSearch}
              onChange={(e) => setAuditSearch(e.target.value)}
              placeholder="Cari no. tiket, plat kendaraan (B 1928 KFL), ID..."
              className="w-full pl-9 pr-3 py-2 bg-white rounded-xl text-[12px] font-['Hanken_Grotesk'] border border-[#e5eeff] focus:outline-none"
            />
          </div>

          {/* Filter Pills */}
          <div className="flex items-center gap-1.5 overflow-x-auto py-1 no-scrollbar">
            {[
              { id: 'all', label: 'Semua' },
              { id: 'transaksi', label: 'Transaksi' },
              { id: 'palang', label: 'Palang Pintu' },
              { id: 'security', label: 'Security' },
              { id: 'emergency', label: 'Emergency' },
            ].map((p) => (
              <button
                key={p.id}
                onClick={() => setAuditFilter(p.id)}
                className={`px-3 py-1.5 rounded-full font-['JetBrains_Mono'] text-[11px] font-bold transition-all text-nowrap ${
                  auditFilter === p.id
                    ? 'bg-[#000922] text-white shadow-sm'
                    : 'bg-white text-[#45464e] border border-[#e5eeff] hover:bg-[#eff4ff]'
                }`}
              >
                {p.label}
              </button>
            ))}
          </div>

          {/* Activity Log Feed */}
          <div className="space-y-2">
            {filteredAuditLogs.map((log) => (
              <article
                key={log.id}
                className="p-3.5 rounded-2xl bg-white shadow-sm border border-[#e5eeff] flex flex-col gap-1.5"
              >
                <div className="flex items-start justify-between">
                  <div className="flex items-center gap-1.5 flex-wrap">
                    <span
                      className={`font-['JetBrains_Mono'] text-[10px] font-bold px-2 py-0.5 rounded-full ${
                        log.severity === 'alert'
                          ? 'bg-[#ffdad6] text-[#ba1a1a]'
                          : log.severity === 'warning'
                          ? 'bg-[#dce9ff] text-[#0051d5]'
                          : 'bg-[#eff4ff] text-[#009c6b]'
                      }`}
                    >
                      {log.gate}
                    </span>
                    <span className="font-['JetBrains_Mono'] text-[10px] px-1.5 py-0.5 rounded bg-[#eff4ff] text-[#45464e] font-semibold border border-[#d3e4fe]">
                      {log.badgeType}
                    </span>
                  </div>
                  <span className="font-['JetBrains_Mono'] text-[11px] text-[#75777f]">
                    {log.timestamp}
                  </span>
                </div>

                <p className="font-['Space_Grotesk'] text-[13px] font-bold text-[#000922]">
                  {log.actionTitle}
                </p>

                {/* Optional details box */}
                {(log.vehiclePlate || log.alertNote || log.authorizer) && (
                  <div className="mt-1 p-2 rounded-xl bg-[#eff4ff] border border-[#d3e4fe] text-[11px] font-['JetBrains_Mono'] text-[#0b1c30] flex flex-col gap-1">
                    {log.vehiclePlate && (
                      <div className="flex items-center gap-2">
                        <span className="font-bold text-[#0051d5]">{log.vehiclePlate}</span>
                        {log.cashierName && (
                          <span className="text-[#45464e]">Petugas: {log.cashierName}</span>
                        )}
                        {log.amount && (
                          <span className="font-bold text-[#009c6b] ml-auto">{log.amount}</span>
                        )}
                      </div>
                    )}
                    {log.alertNote && (
                      <p className="text-[11px] text-[#ba1a1a] font-medium leading-tight">
                        {log.alertNote}
                      </p>
                    )}
                    {log.authorizer && (
                      <span className="text-[10px] text-[#75777f]">
                        OTORISASI: {log.authorizer}
                      </span>
                    )}
                  </div>
                )}
              </article>
            ))}
          </div>

          {/* Action strip */}
          <div className="flex items-center gap-2 pt-2">
            <button
              onClick={() => onShowToast('Log audit berhasil di-refresh', 'success')}
              className="flex-1 py-2.5 rounded-xl bg-white hover:bg-[#eff4ff] text-[#000922] font-['Space_Grotesk'] text-[13px] font-bold border border-[#d3e4fe] flex items-center justify-center gap-1.5 shadow-sm"
            >
              <RotateCw className="w-4 h-4 text-[#0051d5]" />
              <span>Refresh Log</span>
            </button>
            <button
              onClick={() => onShowToast('Mengekspor file log audit (CSV/PDF)...', 'info')}
              className="flex-1 py-2.5 rounded-xl bg-[#000922] hover:bg-[#0f2042] text-white font-['Space_Grotesk'] text-[13px] font-bold flex items-center justify-center gap-1.5 shadow-sm"
            >
              <Download className="w-4 h-4 text-[#6ffbbe]" />
              <span>Unduh CSV/PDF</span>
            </button>
          </div>
        </section>
      )}

      {/* MODAL: EDIT / NEW TARIFF */}
      {editingTariff && (
        <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
          <div className="w-full max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff] max-h-[85vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-2 border-b border-[#e5eeff]">
              <h3 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Edit Tarif: {editingTariff.categoryName}
              </h3>
              <button
                onClick={() => setEditingTariff(null)}
                className="w-8 h-8 rounded-lg flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveModalTariff} className="space-y-3 pt-3">
              <div>
                <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                  Nama Skema / Kategori
                </label>
                <input
                  type="text"
                  value={editingTariff.categoryName}
                  onChange={(e) =>
                    setEditingTariff({ ...editingTariff, categoryName: e.target.value })
                  }
                  className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-bold text-[#000922] border border-[#d3e4fe]"
                />
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    1 Jam Pertama (Rp)
                  </label>
                  <input
                    type="number"
                    value={editingTariff.firstHourRate}
                    onChange={(e) =>
                      setEditingTariff({
                        ...editingTariff,
                        firstHourRate: Number(e.target.value),
                      })
                    }
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Jam Berikutnya (Rp)
                  </label>
                  <input
                    type="number"
                    value={editingTariff.nextHourRate}
                    onChange={(e) =>
                      setEditingTariff({
                        ...editingTariff,
                        nextHourRate: Number(e.target.value),
                      })
                    }
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Maks 24 Jam (Rp)
                  </label>
                  <input
                    type="number"
                    value={editingTariff.maxDailyRate || 50000}
                    onChange={(e) =>
                      setEditingTariff({
                        ...editingTariff,
                        maxDailyRate: Number(e.target.value),
                      })
                    }
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Denda Hilang (Rp)
                  </label>
                  <input
                    type="number"
                    value={editingTariff.lostTicketFee}
                    onChange={(e) =>
                      setEditingTariff({
                        ...editingTariff,
                        lostTicketFee: Number(e.target.value),
                      })
                    }
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setEditingTariff(null)}
                  className="px-4 py-2 bg-[#eff4ff] rounded-xl text-sm font-semibold text-[#45464e]"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#000922] text-white rounded-xl text-sm font-semibold flex items-center gap-1.5 shadow-sm"
                >
                  <Check className="w-4 h-4" />
                  Simpan Tarif
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: TAMBAH AREA BARU */}
      {isAreaModalOpen && (
        <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
          <div className="w-full max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff]">
            <div className="flex items-center justify-between pb-2 border-b border-[#e5eeff]">
              <h3 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                Tambah Area Parkir Baru
              </h3>
              <button
                onClick={() => setIsAreaModalOpen(false)}
                className="w-8 h-8 rounded-lg flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleAddArea} className="space-y-3 pt-3">
              <div>
                <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                  Nama / Kode Area
                </label>
                <input
                  type="text"
                  required
                  value={newAreaName}
                  onChange={(e) => setNewAreaName(e.target.value)}
                  placeholder="Contoh: Rooftop C / Basement B"
                  className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-bold text-[#000922] border border-[#d3e4fe]"
                />
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Peruntukan Kendaraan
                  </label>
                  <select
                    value={newAreaType}
                    onChange={(e) => setNewAreaType(e.target.value)}
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-xs font-semibold text-[#000922] border border-[#d3e4fe]"
                  >
                    <option value="Mobil (Roda 4)">Mobil (Roda 4)</option>
                    <option value="Motor (Roda 2)">Motor (Roda 2)</option>
                    <option value="Campuran / Heavy">Campuran / Heavy</option>
                    <option value="Khusus VIP / Difabel">Khusus VIP / Difabel</option>
                  </select>
                </div>
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Total Slot
                  </label>
                  <input
                    type="number"
                    required
                    value={newAreaCapacity}
                    onChange={(e) => setNewAreaCapacity(e.target.value)}
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setIsAreaModalOpen(false)}
                  className="px-4 py-2 bg-[#eff4ff] rounded-xl text-sm font-semibold text-[#45464e]"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#000922] text-white rounded-xl text-sm font-semibold flex items-center gap-1.5 shadow-sm"
                >
                  <Check className="w-4 h-4" />
                  Daftarkan Area
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL: TAMBAH / EDIT USER */}
      {isUserModalOpen && (
        <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
          <div className="w-full max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff]">
            <div className="flex items-center justify-between pb-2 border-b border-[#e5eeff]">
              <h3 className="font-['Space_Grotesk'] text-[16px] font-bold text-[#000922]">
                {editingUser ? `Edit Akun: ${editingUser.name}` : 'Tambah Akun Petugas Baru'}
              </h3>
              <button
                onClick={() => setIsUserModalOpen(false)}
                className="w-8 h-8 rounded-lg flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveUser} className="space-y-3 pt-3">
              <div>
                <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                  Nama Lengkap
                </label>
                <input
                  type="text"
                  required
                  value={userName}
                  onChange={(e) => setUserName(e.target.value)}
                  placeholder="Contoh: Rian Pratama"
                  className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-bold text-[#000922] border border-[#d3e4fe]"
                />
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Username
                  </label>
                  <input
                    type="text"
                    required
                    value={userUsername}
                    onChange={(e) => setUserUsername(e.target.value)}
                    placeholder="rian_pos"
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm font-['JetBrains_Mono'] font-bold border border-[#d3e4fe]"
                  />
                </div>
                <div>
                  <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                    Hak Akses (Role)
                  </label>
                  <select
                    value={userRole}
                    onChange={(e) => setUserRole(e.target.value as any)}
                    className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-xs font-semibold text-[#000922] border border-[#d3e4fe]"
                  >
                    <option value="Petugas">Petugas POS Kasir</option>
                    <option value="Admin">Admin Operasional</option>
                    <option value="Owner">Owner / Direksi</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="text-[11px] font-['JetBrains_Mono'] text-[#45464e] font-bold">
                  Penempatan / Unit Kerja
                </label>
                <input
                  type="text"
                  value={userUnit}
                  onChange={(e) => setUserUnit(e.target.value)}
                  placeholder="POS Gate Utama"
                  className="w-full mt-1 p-2.5 bg-[#eff4ff] rounded-xl text-sm text-[#000922] border border-[#d3e4fe]"
                />
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setIsUserModalOpen(false)}
                  className="px-4 py-2 bg-[#eff4ff] rounded-xl text-sm font-semibold text-[#45464e]"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#000922] text-white rounded-xl text-sm font-semibold flex items-center gap-1.5 shadow-sm"
                >
                  <Check className="w-4 h-4" />
                  Simpan Akun
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* DENAH MODAL */}
      <DenahModal
        isOpen={Boolean(selectedDenahArea)}
        onClose={() => setSelectedDenahArea(null)}
        area={selectedDenahArea}
        onUpdateAreaSlots={(areaId, newOccupied) => {
          setAreas((prev) =>
            prev.map((a) =>
              a.id === areaId ? { ...a, occupiedSlots: newOccupied } : a
            )
          );
        }}
      />
    </div>
  );
};
