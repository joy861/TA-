<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class ThermalPrinterService
{
    protected string $device;

    public function __construct()
    {
        // Ganti dengan hasil ls /dev/usb* atau /dev/tty.usb* kamu
        $this->device = config('printer.device', '/dev/usb/lp0');
    }

    protected function getConnector()
    {
        $device = $this->device;

        if (preg_match('/^(tcp|network):\/\//i', $device)) {
            $url = parse_url($device);
            if (!isset($url['host']) || !isset($url['port'])) {
                throw new \RuntimeException('Format network printer address tidak valid. Gunakan tcp://host:port.');
            }
            return new NetworkPrintConnector($url['host'], $url['port']);
        }

        if (strpos($device, ':') !== false && substr($device, 0, 1) !== '/') {
            [$host, $port] = explode(':', $device, 2);
            return new NetworkPrintConnector($host, (int) $port);
        }

        if (!file_exists($device) || !is_readable($device)) {
            throw new \RuntimeException('Printer thermal tidak tersedia: ' . $device . '. Pastikan path device benar dan PHP punya izin membaca device.');
        }

        return new FilePrintConnector($device);
    }

    public function cetakDapur(array $data): void
    {
        $connector = $this->getConnector();
        $printer   = new Printer($connector);

        try {
            // === HEADER ===
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->setTextSize(1, 1);
            $printer->text("*** DAPUR ***\n");
            $printer->setEmphasis(false);
            $printer->text("PANDE HILL - Garden View\n");
            $printer->text($this->garis('dashed') . "\n");

            // === INFO ===
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($this->kolom("No. Pesanan", "#" . $data['id_pesanan']) . "\n");
            $printer->text($this->kolom("Meja", $data['nama_meja']) . "\n");
            $printer->text($this->kolom("Waktu", $data['waktu']) . "\n");
            $printer->text($this->garis('dashed') . "\n");

            // === MENU BARU ===
            if (!empty($data['detail_baru'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setEmphasis(true);
                $printer->setTextSize(1, 2); // tinggi 2x
                $printer->text("!! MASAK SEKARANG !!\n");
                $printer->setTextSize(1, 1);
                $printer->setEmphasis(false);
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                foreach ($data['detail_baru'] as $item) {
                    $printer->setEmphasis(true);
                    $printer->text($this->kolom(">> " . $item['nama'], $item['jumlah'] . "x") . "\n");
                    $printer->setEmphasis(false);
                }
                $printer->text($this->garis('line') . "\n");
            }

            // === TAMBAH QTY ===
            if (!empty($data['detail_tambah'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setEmphasis(true);
                $printer->text("+ TAMBAH PORSI\n");
                $printer->setEmphasis(false);
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                foreach ($data['detail_tambah'] as $item) {
                    $printer->text($this->kolom($item['nama'], "+" . $item['tambah'] . "x (total " . $item['jumlah'] . "x)") . "\n");
                }
                $printer->text($this->garis('line') . "\n");
            }

            // === SUDAH DIMASAK ===
            if (!empty($data['detail_lama'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Sudah Dimasak\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                foreach ($data['detail_lama'] as $item) {
                    $printer->text($this->kolom($item['nama'], $item['jumlah'] . "x") . "\n");
                }
                $printer->text($this->garis('line') . "\n");
            }

            // === FOOTER ===
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($this->garis('dashed') . "\n");
            $printer->text("- Selesaikan dengan cepat! -\n");
            $printer->text(now()->timezone('Asia/Makassar')->format('H:i') . " WIT\n");

            // === CUT ===
            $printer->feed(3);
            $printer->cut();

        } finally {
            $printer->close();
        }
    }

    public function cetakStruk(array $data): void
    {
        $connector = $this->getConnector();
        $printer   = new Printer($connector);

        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("PANDE HILL GARDEN VIEW\n");
            $printer->text("STRUK PEMBAYARAN\n");
            $printer->setEmphasis(false);
            $printer->text($this->garis('dashed') . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($this->kolom("No. Pesanan", "#" . $data['id_pesanan']) . "\n");
            $printer->text($this->kolom("Meja", $data['nama_meja']) . "\n");
            $printer->text($this->kolom("Kasir", $data['kasir']) . "\n");
            $printer->text($this->kolom("Waktu", $data['waktu']) . "\n");
            $printer->text($this->kolom("Metode", $data['metode']) . "\n");
            $printer->text($this->garis('dashed') . "\n");

            foreach ($data['detail'] as $item) {
                $printer->text($item['nama'] . "\n");
                $printer->text($this->kolom($item['jumlah'] . 'x @ Rp' . number_format($item['harga'], 0, ',', '.'), 'Rp ' . number_format($item['subtotal'], 0, ',', '.')) . "\n");
            }

            $printer->text($this->garis('dashed') . "\n");
            $printer->text($this->kolom("TOTAL", 'Rp ' . number_format($data['total'], 0, ',', '.')) . "\n");
            $printer->text($this->kolom("BAYAR", 'Rp ' . number_format($data['bayar'], 0, ',', '.')) . "\n");
            $printer->text($this->kolom("KEMBALIAN", 'Rp ' . number_format($data['kembalian'], 0, ',', '.')) . "\n");
            $printer->text($this->garis('dashed') . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("TERIMA KASIH\n");
            $printer->text("Sampai jumpa kembali!\n");

            $printer->feed(3);
            $printer->cut();

        } finally {
            $printer->close();
        }
    }

    // Helper: baris 2 kolom (kiri & kanan), lebar 32 karakter
    private function kolom(string $kiri, string $kanan, int $lebar = 32): string
    {
        $sisa = $lebar - mb_strlen($kiri) - mb_strlen($kanan);
        if ($sisa < 1) $sisa = 1;
        return $kiri . str_repeat(' ', $sisa) . $kanan;
    }

    // Helper: garis pemisah
    private function garis(string $tipe = 'dashed', int $lebar = 32): string
    {
        return match($tipe) {
            'dashed' => str_repeat('-', $lebar),
            'line'   => str_repeat('=', $lebar),
            default  => str_repeat('-', $lebar),
        };
    }
}