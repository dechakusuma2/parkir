import React, { useState } from 'react';
import { ParkingArea } from '../types';
import { X, Car, RefreshCw, CheckCircle2, AlertCircle, Info } from 'lucide-react';

interface DenahModalProps {
  isOpen: boolean;
  onClose: () => void;
  area: ParkingArea | null;
  onUpdateAreaSlots?: (areaId: string, newOccupied: number) => void;
}

/** Menampilkan simulasi denah slot parkir dan memperbarui status slot terpilih. */
export const DenahModal: React.FC<DenahModalProps> = ({
  isOpen,
  onClose,
  area,
  onUpdateAreaSlots,
}) => {
  if (!isOpen || !area) return null;

  // Menentukan jumlah slot simulasi dan jumlah awal slot yang terisi.
  const totalStalls = area.code === 'VIP' ? 15 : area.code === 'A' ? 24 : 32;
  const initialOccupiedCount = Math.round(
    (area.occupiedSlots / area.totalSlots) * totalStalls
  );

  const [occupiedSlots, setOccupiedSlots] = useState<Record<number, boolean>>(() => {
    const map: Record<number, boolean> = {};
    for (let i = 1; i <= totalStalls; i++) {
      map[i] = i <= initialOccupiedCount;
    }
    return map;
  });

  const [selectedSlot, setSelectedSlot] = useState<number | null>(null);

  // Mengubah status slot dan meneruskan jumlah terisi ke pemanggil.
  const toggleSlot = (slotNum: number) => {
    setOccupiedSlots((prev) => {
      const next = { ...prev, [slotNum]: !prev[slotNum] };
      // update count
      const newOccupiedCount = Object.values(next).filter(Boolean).length;
      const scaledOccupied = Math.min(
        area.totalSlots,
        Math.round((newOccupiedCount / totalStalls) * area.totalSlots)
      );
      if (onUpdateAreaSlots) {
        onUpdateAreaSlots(area.id, scaledOccupied);
      }
      return next;
    });
    setSelectedSlot(slotNum);
  };

  const occupiedCount = Object.values(occupiedSlots).filter(Boolean).length;
  const availableCount = totalStalls - occupiedCount;

  return (
    <div className="fixed inset-0 z-50 bg-[#000922]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4 animate-in fade-in duration-200">
      <div className="w-full max-w-lg bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-[#e5eeff] max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="flex items-center justify-between pb-3 border-b border-[#e5eeff]">
          <div className="flex items-center gap-2.5">
            <div className="w-9 h-9 rounded-xl bg-[#eff4ff] text-[#0051d5] flex items-center justify-center font-bold font-['Space_Grotesk'] text-lg">
              {area.code}
            </div>
            <div>
              <h3 className="font-['Space_Grotesk'] text-[17px] font-bold text-[#000922]">
                Denah Interaktif: {area.name}
              </h3>
              <p className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">
                {area.type} • {area.iotType}
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-xl flex items-center justify-center text-[#75777f] hover:bg-[#eff4ff] hover:text-[#000922] transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Live Slot Status Summary */}
        <div className="grid grid-cols-2 gap-2 my-3">
          <div className="p-2.5 bg-[#eff4ff] rounded-xl border border-[#d3e4fe] flex items-center justify-between">
            <div className="flex items-center gap-2">
              <span className="w-2.5 h-2.5 rounded-full bg-[#009c6b] animate-pulse"></span>
              <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">Tersedia:</span>
            </div>
            <span className="font-['JetBrains_Mono'] text-[14px] font-bold text-[#009c6b]">
              {availableCount} Slot
            </span>
          </div>
          <div className="p-2.5 bg-[#ffdad6]/40 rounded-xl border border-[#ffdad6] flex items-center justify-between">
            <div className="flex items-center gap-2">
              <span className="w-2.5 h-2.5 rounded-full bg-[#ba1a1a]"></span>
              <span className="font-['Hanken_Grotesk'] text-[12px] text-[#45464e]">Terisi:</span>
            </div>
            <span className="font-['JetBrains_Mono'] text-[14px] font-bold text-[#ba1a1a]">
              {occupiedCount} Slot
            </span>
          </div>
        </div>

        {/* Floor Map Simulation Canvas */}
        <div className="p-4 bg-[#f8f9ff] rounded-2xl border border-[#e5eeff] space-y-3">
          <div className="flex items-center justify-between text-[11px] font-['JetBrains_Mono'] text-[#75777f]">
            <span className="flex items-center gap-1">
              <Info className="w-3.5 h-3.5 text-[#0051d5]" />
              Klik slot untuk simulasi deteksi sensor ultrasonic IoT
            </span>
            <span>KORIDOR UTAMA ➔</span>
          </div>

          <div className="grid grid-cols-4 sm:grid-cols-6 gap-2">
            {Array.from({ length: totalStalls }).map((_, idx) => {
              const slotNum = idx + 1;
              const isOccupied = occupiedSlots[slotNum];
              const slotCode = `${area.code}-${String(slotNum).padStart(2, '0')}`;
              const isSelected = selectedSlot === slotNum;

              return (
                <button
                  key={slotNum}
                  onClick={() => toggleSlot(slotNum)}
                  className={`relative p-2 rounded-xl border flex flex-col items-center justify-center transition-all ${
                    isOccupied
                      ? 'bg-[#ffdad6]/60 border-[#ffdad6] text-[#93000a]'
                      : 'bg-white border-[#d3e4fe] text-[#009c6b] hover:border-[#0051d5]'
                  } ${isSelected ? 'ring-2 ring-[#0051d5] scale-105 shadow-md' : 'shadow-xs'}`}
                >
                  <span className="font-['JetBrains_Mono'] text-[10px] font-bold">
                    {slotCode}
                  </span>
                  <div className="my-1">
                    <Car
                      className={`w-5 h-5 ${
                        isOccupied ? 'text-[#ba1a1a]' : 'text-[#75777f]/40'
                      }`}
                    />
                  </div>
                  <span className="font-['JetBrains_Mono'] text-[8px] font-semibold uppercase tracking-wider">
                    {isOccupied ? 'TERISI' : 'KOSONG'}
                  </span>
                </button>
              );
            })}
          </div>

          <div className="flex items-center justify-between text-[11px] text-[#45464e] pt-1">
            <div className="flex items-center gap-2">
              <span className="flex items-center gap-1">
                <span className="w-2.5 h-2.5 rounded bg-white border border-[#d3e4fe]"></span>
                Tersedia
              </span>
              <span className="flex items-center gap-1">
                <span className="w-2.5 h-2.5 rounded bg-[#ffdad6] border border-[#ba1a1a]/30"></span>
                Terisi
              </span>
            </div>
            <button
              onClick={() => {
                // Menghasilkan pola keterisian acak untuk simulasi sensor.
                const next: Record<number, boolean> = {};
                for (let i = 1; i <= totalStalls; i++) {
                  next[i] = Math.random() > 0.4;
                }
                setOccupiedSlots(next);
              }}
              className="text-[#0051d5] hover:underline font-['JetBrains_Mono'] font-bold flex items-center gap-1"
            >
              <RefreshCw className="w-3 h-3" />
              Acak Sensor
            </button>
          </div>
        </div>

        {/* Selected Slot Inspector */}
        {selectedSlot && (
          <div className="mt-3 p-3 bg-[#eff4ff] rounded-xl border border-[#b6c6f1] flex items-center justify-between text-[13px]">
            <div>
              <p className="font-bold text-[#000922]">
                Slot {area.code}-{String(selectedSlot).padStart(2, '0')}
              </p>
              <p className="text-[12px] text-[#45464e]">
                Status Sensor:{' '}
                <span className={occupiedSlots[selectedSlot] ? 'text-[#ba1a1a] font-bold' : 'text-[#009c6b] font-bold'}>
                  {occupiedSlots[selectedSlot] ? 'Mendeteksi Kendaraan Aktif' : 'Sensor Ultrasonik Kosong'}
                </span>
              </p>
            </div>
            <button
              onClick={() => toggleSlot(selectedSlot)}
              className="px-2.5 py-1 rounded-lg bg-[#000922] text-white text-[11px] font-bold"
            >
              Toggle Status
            </button>
          </div>
        )}

        <div className="mt-4 flex justify-end">
          <button
            onClick={onClose}
            className="w-full py-2.5 rounded-xl bg-[#0f2042] text-white font-semibold text-sm hover:bg-[#000922] transition-colors"
          >
            Tutup Denah
          </button>
        </div>
      </div>
    </div>
  );
};
