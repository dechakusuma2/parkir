/** Peran yang tersedia pada antarmuka simulasi parkir. */
export type UserRole = 'petugas' | 'admin' | 'owner';

/** Tab utama yang dapat dibuka melalui navigasi aplikasi. */
export type NavTab = 'overview' | 'pos-petugas' | 'admin-master' | 'owner-rekap';

/** Bentuk data tarif dan aturan biaya untuk satu kategori kendaraan. */
export interface VehicleTariff {
  id: string;
  categoryName: string;
  type: 'motor' | 'mobil' | 'truk';
  tag: string;
  description: string;
  firstHourRate: number;
  nextHourRate: number;
  maxDailyRate?: number;
  lostTicketFee: number;
  allowedArea?: string;
}

/** Bentuk data kapasitas, status, dan sensor pada satu area parkir. */
export interface ParkingArea {
  id: string;
  name: string;
  code: string;
  type: string;
  totalSlots: number;
  occupiedSlots: number;
  statusBadge: string;
  badgeType: 'error' | 'secondary' | 'success';
  iotType: string;
  iotNodes: number;
  isRfidOnly?: boolean;
}

/** Informasi kategori kendaraan yang ditampilkan sebagai data referensi. */
export interface VehicleCategory {
  id: string;
  code: string;
  title: string;
  description: string;
  imageUrl: string;
}

/** Identitas, peran, status, dan informasi masuk pengguna staf. */
export interface StaffUser {
  id: string;
  name: string;
  username: string;
  role: 'Petugas' | 'Admin' | 'Owner';
  unit: string;
  status: 'Aktif' | 'Nonaktif';
  lastLogin: string;
  initials: string;
}

/** Rincian catatan audit untuk transaksi atau aktivitas gerbang. */
export interface AuditLogItem {
  id: string;
  timestamp: string;
  gate: string;
  badgeType: string;
  actionTitle: string;
  vehiclePlate?: string;
  vehicleType?: string;
  amount?: string;
  cashierName?: string;
  authorizer?: string;
  alertNote?: string;
  category: 'transaksi' | 'palang' | 'security' | 'emergency';
  severity: 'normal' | 'warning' | 'alert';
}

/** Rincian tiket parkir dari kendaraan masuk hingga proses pembayaran. */
export interface ParkingTicket {
  ticketNumber: string;
  plateNumber: string;
  vehicleType: 'motor' | 'mobil' | 'truk';
  vehicleLabel: string;
  entryTime: string;
  exitTime?: string;
  durationHours?: number;
  durationMinutes?: number;
  rateFirstHour: number;
  totalAmount: number;
  gate: string;
  cashierName: string;
  status: 'in' | 'paid';
  paymentMethod?: 'cash' | 'qris' | 'emoney';
}
