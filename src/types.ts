export type UserRole = 'petugas' | 'admin' | 'owner';

export type NavTab = 'overview' | 'pos-petugas' | 'admin-master' | 'owner-rekap';

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

export interface VehicleCategory {
  id: string;
  code: string;
  title: string;
  description: string;
  imageUrl: string;
}

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
