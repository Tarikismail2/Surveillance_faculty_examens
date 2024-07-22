<?php

// namespace App\Jobs;

// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\StudentsImport;
// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;

// class ImportStudents implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     protected $filePath;

//     public function __construct($filePath)
//     {
//         $this->filePath = $filePath;
//     }

//     public function handle()
//     {
//         \Log::info('Job started for file: ' . $this->filePath);
//         try {
//             Excel::import(new StudentsImport, storage_path('app/public/' . $this->filePath), null, \Maatwebsite\Excel\Excel::XLSX)->chunk(100);
//             \Log::info('File processed successfully: ' . $this->filePath);
//         } catch (\Exception $e) {
//             \Log::error('Error processing file: ' . $e->getMessage());
//         }
//     }
// }
