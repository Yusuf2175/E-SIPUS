<?php

namespace App\Services;

class LibraryLocationService
{
    /**
     * Daftar provinsi beserta kata kunci pengenalnya dari teks alamat bebas.
     * Key = nama provinsi canonical, value = array kata kunci (lowercase).
     */
    private array $provinces = [
        'Aceh'                       => ['aceh', 'banda aceh', 'nanggroe'],
        'Bali'                       => ['bali', 'denpasar', 'singaraja', 'gianyar', 'tabanan', 'badung', 'klungkung', 'bangli', 'karangasem', 'jembrana', 'buleleng'],
        'Banten'                     => ['banten', 'serang', 'tangerang', 'cilegon', 'lebak', 'pandeglang'],
        'Bengkulu'                   => ['bengkulu', 'rejang lebong', 'kepahiang', 'seluma', 'kaur', 'lebong', 'mukomuko'],
        'DI Yogyakarta'              => ['yogyakarta', 'jogja', 'sleman', 'bantul', 'gunung kidul', 'kulon progo', 'wonosari'],
        'DKI Jakarta'                => ['jakarta', 'dki', 'jakarta pusat', 'jakarta utara', 'jakarta selatan', 'jakarta barat', 'jakarta timur', 'kepulauan seribu'],
        'Gorontalo'                  => ['gorontalo', 'pohuwato', 'bone bolango', 'boalemo'],
        'Jambi'                      => ['jambi', 'batanghari', 'muaro jambi', 'tanjung jabung', 'sarolangun', 'merangin', 'bungo', 'tebo', 'kerinci', 'sungai penuh'],
        'Jawa Barat'                 => ['jawa barat', 'jabar', 'bandung', 'bogor', 'bekasi', 'depok', 'cimahi', 'sukabumi', 'cirebon', 'tasikmalaya', 'garut', 'cianjur', 'subang', 'purwakarta', 'karawang', 'indramayu', 'majalengka', 'sumedang', 'kuningan', 'ciamis', 'pangandaran', 'banjar'],
        'Jawa Tengah'                => ['jawa tengah', 'jateng', 'semarang', 'solo', 'surakarta', 'magelang', 'salatiga', 'pekalongan', 'tegal', 'kudus', 'jepara', 'demak', 'kendal', 'batang', 'pemalang', 'brebes', 'cilacap', 'banyumas', 'purbalingga', 'banjarnegara', 'kebumen', 'purworejo', 'wonosobo', 'temanggung', 'klaten', 'boyolali', 'sragen', 'grobogan', 'blora', 'rembang', 'pati', 'wonogiri', 'karanganyar'],
        'Jawa Timur'                 => ['jawa timur', 'jatim', 'surabaya', 'malang', 'sidoarjo', 'gresik', 'mojokerto', 'pasuruan', 'probolinggo', 'batu', 'blitar', 'kediri', 'madiun', 'jember', 'banyuwangi', 'situbondo', 'bondowoso', 'lumajang', 'jombang', 'nganjuk', 'tulungagung', 'trenggalek', 'ponorogo', 'magetan', 'ngawi', 'bojonegoro', 'tuban', 'lamongan', 'bangkalan', 'sampang', 'pamekasan', 'sumenep', 'pacitan'],
        'Kalimantan Barat'           => ['kalimantan barat', 'kalbar', 'pontianak', 'singkawang', 'sambas', 'bengkayang', 'landak', 'mempawah', 'sanggau', 'sekadau', 'sintang', 'melawi', 'kapuas hulu', 'ketapang', 'kayong utara', 'kubu raya'],
        'Kalimantan Selatan'         => ['kalimantan selatan', 'kalsel', 'banjarmasin', 'banjarbaru', 'banjar', 'barito kuala', 'tanah laut', 'kotabaru', 'tanah bumbu', 'hulu sungai', 'tabalong', 'balangan', 'tapin'],
        'Kalimantan Tengah'          => ['kalimantan tengah', 'kalteng', 'palangka raya', 'palangkaraya', 'kotawaringin', 'kapuas', 'barito', 'murung raya', 'gunung mas', 'katingan', 'seruyan', 'lamandau', 'sukamara', 'pulang pisau'],
        'Kalimantan Timur'           => ['kalimantan timur', 'kaltim', 'samarinda', 'balikpapan', 'bontang', 'kutai', 'berau', 'paser', 'penajam', 'mahakam ulu'],
        'Kalimantan Utara'           => ['kalimantan utara', 'kaltara', 'tarakan', 'bulungan', 'malinau', 'nunukan', 'tana tidung'],
        'Kepulauan Bangka Belitung'  => ['bangka', 'belitung', 'babel', 'pangkalpinang', 'bangka barat', 'bangka tengah', 'bangka selatan', 'belitung timur'],
        'Kepulauan Riau'             => ['kepulauan riau', 'kepri', 'batam', 'tanjungpinang', 'bintan', 'karimun', 'natuna', 'lingga', 'anambas'],
        'Lampung'                    => ['lampung', 'bandar lampung', 'metro', 'pringsewu', 'pesawaran', 'tanggamus', 'lampung selatan', 'lampung tengah', 'lampung utara', 'lampung barat', 'lampung timur', 'tulang bawang', 'mesuji', 'way kanan', 'pesisir barat'],
        'Maluku'                     => ['maluku', 'ambon', 'ternate', 'tual', 'buru', 'seram', 'maluku tengah', 'maluku tenggara', 'kepulauan aru', 'maluku barat daya'],
        'Maluku Utara'               => ['maluku utara', 'ternate', 'tidore', 'halmahera', 'morotai', 'sula', 'bacan'],
        'Nusa Tenggara Barat'        => ['nusa tenggara barat', 'ntb', 'mataram', 'lombok', 'sumbawa', 'dompu', 'bima'],
        'Nusa Tenggara Timur'        => ['nusa tenggara timur', 'ntt', 'kupang', 'flores', 'timor', 'sumba', 'ende', 'manggarai', 'sikka', 'ngada', 'nagekeo', 'alor', 'lembata', 'rote ndao', 'sabu raijua', 'malaka'],
        'Papua'                      => ['papua', 'jayapura', 'merauke', 'mimika', 'biak', 'nabire', 'sarmi', 'keerom', 'waropen', 'supiori', 'mamberamo'],
        'Papua Barat'                => ['papua barat', 'manokwari', 'sorong', 'fakfak', 'kaimana', 'teluk bintuni', 'teluk wondama', 'tambrauw', 'maybrat', 'raja ampat'],
        'Papua Barat Daya'           => ['papua barat daya', 'sorong selatan', 'maybrat', 'tambrauw'],
        'Papua Pegunungan'           => ['papua pegunungan', 'jayawijaya', 'pegunungan bintang', 'tolikara', 'yahukimo', 'yalimo', 'lanny jaya', 'nduga', 'puncak', 'puncak jaya', 'mamberamo tengah'],
        'Papua Selatan'              => ['papua selatan', 'merauke', 'boven digoel', 'mappi', 'asmat'],
        'Papua Tengah'               => ['papua tengah', 'nabire', 'paniai', 'dogiyai', 'deiyai', 'intan jaya', 'mimika'],
        'Riau'                       => ['riau', 'pekanbaru', 'dumai', 'bengkalis', 'siak', 'kampar', 'rokan', 'pelalawan', 'indragiri', 'kuantan singingi', 'kepulauan meranti'],
        'Sulawesi Barat'             => ['sulawesi barat', 'sulbar', 'mamuju', 'majene', 'polewali mandar', 'mamasa', 'pasangkayu', 'mamuju tengah'],
        'Sulawesi Selatan'           => ['sulawesi selatan', 'sulsel', 'makassar', 'parepare', 'palopo', 'gowa', 'maros', 'pangkep', 'barru', 'bone', 'soppeng', 'wajo', 'sinjai', 'bulukumba', 'bantaeng', 'jeneponto', 'takalar', 'selayar', 'pinrang', 'enrekang', 'tana toraja', 'luwu', 'sidenreng rappang'],
        'Sulawesi Tengah'            => ['sulawesi tengah', 'sulteng', 'palu', 'donggala', 'sigi', 'parigi moutong', 'poso', 'tojo una-una', 'morowali', 'banggai', 'buol', 'tolitoli'],
        'Sulawesi Tenggara'          => ['sulawesi tenggara', 'sultra', 'kendari', 'baubau', 'konawe', 'kolaka', 'muna', 'buton', 'bombana', 'wakatobi', 'kolaka utara', 'konawe utara', 'konawe selatan'],
        'Sulawesi Utara'             => ['sulawesi utara', 'sulut', 'manado', 'bitung', 'tomohon', 'kotamobagu', 'minahasa', 'bolaang mongondow', 'sangihe', 'talaud', 'sitaro'],
        'Sumatera Barat'             => ['sumatera barat', 'sumbar', 'padang', 'bukittinggi', 'payakumbuh', 'solok', 'sawahlunto', 'pariaman', 'padang panjang', 'agam', 'tanah datar', 'lima puluh kota', 'pasaman', 'sijunjung', 'dharmasraya', 'pesisir selatan', 'solok selatan', 'kepulauan mentawai'],
        'Sumatera Selatan'           => ['sumatera selatan', 'sumsel', 'palembang', 'prabumulih', 'pagar alam', 'lubuklinggau', 'ogan', 'muara enim', 'lahat', 'musi', 'banyuasin', 'empat lawang', 'penukal abab'],
        'Sumatera Utara'             => ['sumatera utara', 'sumut', 'medan', 'binjai', 'tebing tinggi', 'pematangsiantar', 'tanjungbalai', 'padangsidimpuan', 'gunungsitoli', 'deli serdang', 'langkat', 'karo', 'simalungun', 'asahan', 'labuhanbatu', 'tapanuli', 'nias', 'mandailing natal', 'humbang hasundutan', 'pakpak bharat', 'samosir', 'toba'],
    ];

    /**
     * Deteksi provinsi dari teks alamat bebas.
     * Mengembalikan nama provinsi canonical atau null jika tidak ditemukan.
     */
    public function detectProvinceFromAddress(?string $address): ?string
    {
        if (!$address) return null;

        $addressLower = strtolower($address);

        // Cari kecocokan terpanjang (lebih spesifik lebih baik)
        $bestMatch = null;
        $bestLength = 0;

        foreach ($this->provinces as $province => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($addressLower, $keyword) && strlen($keyword) > $bestLength) {
                    $bestMatch = $province;
                    $bestLength = strlen($keyword);
                }
            }
        }

        return $bestMatch;
    }

    /**
     * Peta provinsi tetangga (berdasarkan kedekatan geografis di pulau yang sama).
     */
    private array $neighbors = [
        'Aceh'                       => ['Sumatera Utara'],
        'Sumatera Utara'             => ['Aceh', 'Sumatera Barat', 'Riau'],
        'Sumatera Barat'             => ['Sumatera Utara', 'Riau', 'Jambi', 'Bengkulu'],
        'Riau'                       => ['Sumatera Utara', 'Sumatera Barat', 'Jambi', 'Kepulauan Riau'],
        'Kepulauan Riau'             => ['Riau'],
        'Jambi'                      => ['Riau', 'Sumatera Barat', 'Bengkulu', 'Sumatera Selatan', 'Lampung'],
        'Bengkulu'                   => ['Sumatera Barat', 'Jambi', 'Sumatera Selatan', 'Lampung'],
        'Sumatera Selatan'           => ['Jambi', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung'],
        'Kepulauan Bangka Belitung'  => ['Sumatera Selatan'],
        'Lampung'                    => ['Sumatera Selatan', 'Bengkulu', 'Banten'],
        'Banten'                     => ['Lampung', 'DKI Jakarta', 'Jawa Barat'],
        'DKI Jakarta'                => ['Banten', 'Jawa Barat'],
        'Jawa Barat'                 => ['Banten', 'DKI Jakarta', 'Jawa Tengah'],
        'Jawa Tengah'                => ['Jawa Barat', 'DI Yogyakarta', 'Jawa Timur'],
        'DI Yogyakarta'              => ['Jawa Tengah', 'Jawa Timur'],
        'Jawa Timur'                 => ['Jawa Tengah', 'DI Yogyakarta', 'Bali'],
        'Bali'                       => ['Jawa Timur', 'Nusa Tenggara Barat'],
        'Nusa Tenggara Barat'        => ['Bali', 'Nusa Tenggara Timur'],
        'Nusa Tenggara Timur'        => ['Nusa Tenggara Barat'],
        'Kalimantan Barat'           => ['Kalimantan Tengah', 'Kalimantan Utara'],
        'Kalimantan Tengah'          => ['Kalimantan Barat', 'Kalimantan Selatan', 'Kalimantan Timur'],
        'Kalimantan Selatan'         => ['Kalimantan Tengah', 'Kalimantan Timur'],
        'Kalimantan Timur'           => ['Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Utara'],
        'Kalimantan Utara'           => ['Kalimantan Barat', 'Kalimantan Timur'],
        'Sulawesi Utara'             => ['Gorontalo', 'Sulawesi Tengah'],
        'Gorontalo'                  => ['Sulawesi Utara', 'Sulawesi Tengah'],
        'Sulawesi Tengah'            => ['Gorontalo', 'Sulawesi Utara', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara'],
        'Sulawesi Barat'             => ['Sulawesi Tengah', 'Sulawesi Selatan'],
        'Sulawesi Selatan'           => ['Sulawesi Barat', 'Sulawesi Tengah', 'Sulawesi Tenggara'],
        'Sulawesi Tenggara'          => ['Sulawesi Selatan', 'Sulawesi Tengah'],
        'Maluku'                     => ['Maluku Utara', 'Papua Barat'],
        'Maluku Utara'               => ['Maluku', 'Sulawesi Utara'],
        'Papua Barat'                => ['Maluku', 'Papua', 'Papua Barat Daya'],
        'Papua Barat Daya'           => ['Papua Barat'],
        'Papua'                      => ['Papua Barat', 'Papua Tengah', 'Papua Pegunungan', 'Papua Selatan'],
        'Papua Tengah'               => ['Papua', 'Papua Pegunungan', 'Papua Selatan'],
        'Papua Pegunungan'           => ['Papua', 'Papua Tengah'],
        'Papua Selatan'              => ['Papua', 'Papua Tengah'],
    ];

    /**
     * Ambil daftar provinsi tetangga dari suatu provinsi.
     *
     * @return string[]
     */
    public function getNeighborProvinces(string $province): array
    {
        return $this->neighbors[$province] ?? [];
    }

    /**
     * Ambil semua nama provinsi canonical.
     */
    public function getProvinces(): array
    {
        return array_keys($this->provinces);
    }
    public function formatName(string $name): string
    {
        return ucwords(strtolower($name));
    }

    /**
     * Gabungkan provinsi dan kota menjadi satu string.
     */
    public function buildRegionLabel(?string $province, ?string $city): string
    {
        if ($province && $city) {
            return "{$city}, {$province}";
        }
        return $province ?? $city ?? '';
    }
}
