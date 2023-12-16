<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;

class EventInvitation extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'event_invitations';
    protected $table_reference = 'event_invitation';
    protected $fillable = ['id','event_id','email','created_at','updated_at','full_name','is_original', 'send_email'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_event',function(Builder $builder){
            $builder
                ->join((new Event())->getTable(),
                    function($join){
                        $join->on('events.id','=','event_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.event_id',
                    (new Event())->getTable().'.name as event_name',
                    (new static())->getTable().'.email',
                    (new static())->getTable().'.full_name',
                    (new static())->getTable().'.is_original',
                    (new static())->getTable().'.send_email',
                );
        });
    }

    public static function GetById($id){
        return EventInvitation::whereRaw('event_invitations.id = ?',[$id])->get();
    }
	public static function GetByEventId($id){
        return EventInvitation::whereRaw('event_invitations.event_id = ?',[$id])->get();
    }
	public static function DeleteByEventId($id){
         return EventInvitation::where('event_invitations.event_id',$id)->delete();
    }

    public static function GetNoSentReminderEvents(){
        return DB::table('event_invitations')->join('events', 'event_invitations.event_id', "=", 'events.id')->select('event_invitations.*')->where('events.isReminderSent', '=', '0')->where('events.start_event', ">", now())->get();
   }
	
}
