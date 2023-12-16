<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;
class Event extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'events';
    protected $table_reference = 'event';
    protected $fillable = ['id','user_id','name','description','banner','start_event','address','end_at','token','created_at','updated_at','address_id','gratitude','banner_gratitude','isReminderSent'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_user',function(Builder $builder){
            $builder
                ->join((new User())->getTable(),
                    function($join){
                        $join->on('users.id','=','user_id');
                    })
                ->join((new Ubication())->getTable(),
                    function($join){
                        $join->on('ubications.id','=','address_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.user_id',
                    (new User())->getTable().'.first_name as user_first_name',
                    (new User())->getTable().'.last_name as user_last_name',
                    (new static())->getTable().'.name',
                    (new static())->getTable().'.description',
                    (new static())->getTable().'.banner',
                    (new static())->getTable().'.token',
                    (new static())->getTable().'.end_at',
                    (new static())->getTable().'.address_id',
                    (new Ubication())->getTable().'.code as address_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Ubication())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as address_name"),
                    (new static())->getTable().'.gratitude',
                    (new static())->getTable().'.banner_gratitude',
                    (new static())->getTable().'.start_event',
                    (new static())->getTable().'.address',
                )->where(
                    (new static())->getTable().'.end_at', ">", now()
                );
        });
    }

    public static function GetByUserId($user_id){
        return Event::whereRaw('events.user_id = ?',[$user_id])->first();
    }
	public static function GetById($id){
        return Event::whereRaw('events.id = ?',[$id])->first();
    }
	public static function GetByToken($token){
        return Event::whereRaw('token = ?',[$token])->first();
    }
	public static function GetByIdUserId($id,$user_id){
        return Event::whereRaw('events.id = ? and events.user_id = ?',[$id,$user_id])->first();
    }
	
	public static function ExistsWithToken($token){
        return count(Event::whereRaw('token = ?', array($token))->get())>0;
    }

	public static function DeleteById($id){
         return Event::where('events.id',$id)->delete();
    }
    public static function GetEventsDate($date_end){
        return Event::WhereBetween((new static)->getTable().'.start_event', [now(), Carbon::parse($date_end)->endOfDay()])->get();
    }

    public static function GetNoSentReminder(){
        /* return Event::where('isReminderSent', '=', 0)->count(); */
        return DB::table('events')->join('users', 'events.user_id', '=', 'users.id')
        ->select('events.*',
                 'users.first_name as user_firstName',  
                 'users.last_name as user_lastName',
        )->where('isReminderSent', '=', '0')->where('events.start_event', ">", now())->get();
    }

    public static function UpdateSentReminder($id){
        return DB::table('events')
        ->where('events.id', "=", $id)
        ->update(['isReminderSent' => true]);
    }
}
