<?php

namespace App;

use App\S3Server;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{

    public function converting()
    {
        return $this->hasOne('App\Converting');
    }

    public function s3server()
    {
        return $this->belongsTo('App\S3Server');
    }
    public function deleteFile()
    {
        unlink(storage_path("app/public/" . $this->filename));
    }

    public function deleteFile_S3()
    {
        $s3server = S3Server::where('can_upload', true)->first();
        config([
            'filesystems.disks.s3.key' => $s3server->key,
            'filesystems.disks.s3.secret' => $s3server->secret,
            'filesystems.disks.s3.region' => $s3server->region,
            'filesystems.disks.s3.bucket' => $s3server->bucket,
            'filesystems.disks.s3.url' => $s3server->url,
            'filesystems.disks.s3.endpoint' => $s3server->endpoint,
        ]);

        Storage::disk('s3')->delete($this->filename);
        $this->delete();
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute($url)
    {
        return $url;
    }

    protected $appends = [

    ];

    protected $casts = [
        'options' => 'json',
    ];

    protected $fillable = [
        'temporary_file_id',
        'url',
        's3server_id',
        'filename',
        'original_filename',
        'filesize',
        'type',
        'owner_type',
        'owner_id',
        'collection_name',
        'options',
        'status',
        's3server_id',
    ];

    protected $hidden = [
        // 'url',
        's3server_id',
        's3server',
        'filename',
        'owner_type',
        //'owner_id',
        // 'collection_name',
        'filesize',
        'created_at',
        'updated_at',
    ];
}
