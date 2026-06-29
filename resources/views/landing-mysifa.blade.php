@extends('layouts.landing')

@section('title', 'MySIFA Official - Tingkatkan Omzet dan Profit Apotek')
@section('meta_description', 'MySIFA membantu apotek meningkatkan omzet dan profit melalui Smart Kasir, Smart Order, Smart Inventory, Smart Report, Smart Trace, dan Stok Opname.')
@section('og_title', 'MySIFA Official - Sistem Teruji Meningkatkan Omzet Apotek')
@section('og_description', 'Sebelum terlambat dan omzet turun lebih jauh, gunakan sistem MySIFA yang sudah teruji meningkatkan transaksi penjualan di apotek.')

@section('content')
    @include('landing.sections.hero')
    @include('landing.sections.tim-problem')
    @include('landing.sections.not-alone')
    @include('landing.sections.smart-inventory')
    @include('landing.sections.why-mysifa')
    @include('landing.sections.cta-strong')
    @include('landing.sections.testimonials')
    @include('landing.sections.footer')
@endsection
