<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:30
 * Email:499873958@qq.com
 */

namespace Xavier\Model\Driver;


trait RelationTrait
{
    /**
     * @param $self_column
     * @param $third
     * @param $third_column
     * @return Model
     */
    protected function hasOne($self_column, $third, $third_column)
    {
        return new HasOne($self_column, $third, $third_column, $this);
    }

    /**
     * @param $self_column
     * @param $third
     * @param $third_column
     * @return Model
     */
    protected function hasMany($self_column, $third, $third_column)
    {
        return new HasMany($self_column, $third, $third_column, $this);
    }

}