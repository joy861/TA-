<?php

namespace App\Services;

use RuntimeException;

class ThermalPrinterService
{
    protected string $shareName;

    public function __construct()
    {
        $this->shareName = config('printer.share_name', 'PrinterKampus');
    }

    public function cetakDapur(array $data): void
    {
        $tipeCetak = $data['tipe_cetak'] ?? 'awal';

        $pesananLama = $data['pesanan_lama'] ?? [];
        $pesananBaru = $data['pesanan_baru'] ?? ($data['detail'] ?? []);

        $text  = "";
        $text .= $this->line("=") . "\n";
        $text .= $this->center("ORDER DAPUR") . "\n";
        $text .= $this->center("PANDE HILL") . "\n";
        $text .= $this->line("=") . "\n";
        $text .= $this->row("No. Pesanan", "#" . ($data['id_pesanan'] ?? '-')) . "\n";
        $text .= $this->row("Meja", $data['nama_meja'] ?? '-') . "\n";
        $text .= $this->row("Waktu", $data['waktu'] ?? '-') . "\n";
        $text .= $this->row("Kasir", $data['kasir'] ?? '-') . "\n";
        $text .= $this->line("-") . "\n";

        if ($tipeCetak === 'update') {
            if (count($pesananLama) > 0) {
                $text .= $this->center("PESANAN SEBELUMNYA") . "\n";
                $text .= $this->center("INFO - JANGAN MASAK ULANG") . "\n";
                $text .= $this->line("-") . "\n";

                foreach ($pesananLama as $item) {
                    $text .= $this->printItemRingkas($item, false);
                }
            }

            $text .= $this->center("UPDATE PESANAN BARU") . "\n";
            $text .= $this->center("PERLU DIMASAK SEKARANG") . "\n";
            $text .= $this->line("-") . "\n";

            foreach ($pesananBaru as $item) {
                $text .= $this->printItemRingkas($item, true);
            }

            $text .= $this->line("=") . "\n";
            $text .= $this->center("Mohon proses update") . "\n";
            $text .= $this->center("pesanan dengan cepat") . "\n";
            $text .= $this->line("=") . "\n";
        } else {
            // Pesanan awal setelah kasir input pertama kali
            $text .= "DAFTAR PESANAN\n";
            $text .= $this->line("-") . "\n";

            foreach ($pesananBaru as $item) {
                $text .= $this->printItemAwal($item);
            }

            $text .= $this->line("=") . "\n";
            $text .= $this->center("Mohon segera diproses") . "\n";
            $text .= $this->line("=") . "\n";
        }

        $text .= "\n\n\n";

        $this->sendToWindowsPrinter($text, 'dapur_' . ($data['id_pesanan'] ?? time()) . '.txt');
    }

    public function cetakStruk(array $data): void
    {
        $text  = "";
        $text .= $this->line("=") . "\n";
        $text .= $this->center("PANDE HILL") . "\n";
        $text .= $this->center("GARDEN VIEW") . "\n";
        $text .= $this->center("STRUK PEMBAYARAN") . "\n";
        $text .= $this->line("=") . "\n";
        $text .= $this->row("No. Pesanan", "#" . ($data['id_pesanan'] ?? '-')) . "\n";
        $text .= $this->row("Meja", $data['nama_meja'] ?? '-') . "\n";
        $text .= $this->row("Kasir", $data['kasir'] ?? '-') . "\n";
        $text .= $this->row("Waktu", $data['waktu'] ?? '-') . "\n";
        $text .= $this->row("Metode", $data['metode'] ?? '-') . "\n";
        $text .= $this->line("-") . "\n";

        foreach (($data['detail'] ?? []) as $item) {
            $nama     = $item['nama'] ?? '-';
            $jumlah   = (int) ($item['jumlah'] ?? 0);
            $harga    = (int) ($item['harga'] ?? 0);
            $subtotal = (int) ($item['subtotal'] ?? 0);

            $text .= $this->wrap($nama) . "\n";
            $text .= $this->row(
                $jumlah . "x Rp" . number_format($harga, 0, ',', '.'),
                "Rp" . number_format($subtotal, 0, ',', '.')
            ) . "\n";
        }

        $subtotal  = (int) ($data['subtotal'] ?? 0);
        $service   = (int) ($data['service'] ?? 0);
        $biayaCard = (int) ($data['biaya_card'] ?? 0);
        $total     = (int) ($data['total'] ?? 0);
        $bayar     = (int) ($data['bayar'] ?? 0);
        $kembalian = (int) ($data['kembalian'] ?? 0);

        $text .= $this->line("-") . "\n";
        $text .= $this->row("SUBTOTAL", "Rp" . number_format($subtotal, 0, ',', '.')) . "\n";
        $text .= $this->row("SERVICE 7%", "Rp" . number_format($service, 0, ',', '.')) . "\n";

        if ($biayaCard > 0) {
            $text .= $this->row("BIAYA CARD", "Rp" . number_format($biayaCard, 0, ',', '.')) . "\n";
        }

        $text .= $this->line("-") . "\n";
        $text .= $this->row("TOTAL", "Rp" . number_format($total, 0, ',', '.')) . "\n";
        $text .= $this->row("BAYAR", "Rp" . number_format($bayar, 0, ',', '.')) . "\n";
        $text .= $this->row("KEMBALIAN", "Rp" . number_format($kembalian, 0, ',', '.')) . "\n";
        $text .= $this->line("=") . "\n";
        $text .= $this->center("TERIMA KASIH") . "\n";
        $text .= "\n\n\n";

        $this->sendToWindowsPrinter($text, 'struk_' . ($data['id_pesanan'] ?? time()) . '.txt');
    }

    private function printItemAwal(array $item): string
    {
        $nama   = $item['nama'] ?? '-';
        $jumlah = (int) ($item['jumlah'] ?? 0);
        $tipe   = strtoupper($item['tipe'] ?? 'NORMAL');

        $text  = $this->wrap($nama) . "\n";
        $text .= "Jumlah : {$jumlah}x\n";
        $text .= "Tipe   : {$tipe}\n";
        $text .= $this->line("-") . "\n";

        return $text;
    }

    private function printItemRingkas(array $item, bool $showJenis = true): string
    {
        $nama   = $item['nama'] ?? '-';
        $jumlah = (int) ($item['jumlah'] ?? 0);
        $tipe   = strtoupper($item['tipe'] ?? 'NORMAL');
        $jenis  = strtoupper($item['jenis'] ?? $item['status'] ?? 'BARU');

        $text  = $this->row($nama, $jumlah . "x") . "\n";

        if ($showJenis) {
            $text .= "Jenis : {$jenis}\n";
        }

        $text .= "Tipe  : {$tipe}\n";
        $text .= $this->line("-") . "\n";

        return $text;
    }

    private function sendToWindowsPrinter(string $content, string $filename): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            throw new RuntimeException('Direct print ini hanya untuk Windows.');
        }

        $folder = storage_path('app/print_jobs');

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $content);

        $printerPath = '\\\\localhost\\' . $this->shareName;

        $command = 'cmd /C copy /B '
            . escapeshellarg($filePath)
            . ' '
            . escapeshellarg($printerPath)
            . ' 2>&1';

        exec($command, $output, $exitCode);

        if ($exitCode !== 0) {
            throw new RuntimeException(
                'Gagal kirim ke printer. Pastikan printer sudah di-share dengan nama '
                . $this->shareName
                . '. Detail: '
                . implode("\n", $output)
            );
        }
    }

    private function line(string $char = '-', int $length = 32): string
    {
        return str_repeat($char, $length);
    }

    private function center(string $text, int $length = 32): string
    {
        $text = trim($text);

        if (strlen($text) >= $length) {
            return substr($text, 0, $length);
        }

        $left = floor(($length - strlen($text)) / 2);

        return str_repeat(' ', $left) . $text;
    }

    private function row(string $left, string $right, int $length = 32): string
    {
        $left  = trim($left);
        $right = trim($right);

        if (strlen($left) > 22) {
            $left = substr($left, 0, 22);
        }

        $space = $length - strlen($left) - strlen($right);

        if ($space < 1) {
            $space = 1;
        }

        return $left . str_repeat(' ', $space) . $right;
    }

    private function wrap(string $text, int $length = 32): string
    {
        return wordwrap(trim($text), $length, "\n", true);
    }
}
