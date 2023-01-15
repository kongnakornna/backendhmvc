<?php
		
		class CI_Memcache {

        function CI_Memcache() {
                $this->CI = & get_instance();
                $this->connect();
        }

        /**
         * Connect to memcache server
         */
        function connect() {
                $this->memcache = new Memcache;
                $this->connected_servers = array ();
                if ($this->CI->config->item('use_memcache')) {
                        $servers = $this->CI->config->item('memcache_servers');
                        if (!empty ($servers)) {
                                foreach ($servers as $server) { 
                                        if ($this->memcache->addServer($server['host'], $server['port'])) {
                                                $this->connected_servers[] = $server;
                                        }
                                }
                        }
                }
        }

        /**
         * Set data
         */
        function set($key, $value, $expire = 30) {
                if (empty ($this->connected_servers)) {
                        return false;
                } else {
                        return $this->memcache->set($key, $value, 0, $expire);
                }
        }

        /**
         * Get data by key
         */
        function get($key) {
                if (empty ($this->connected_servers)) {
                        return false;
                } else {
                        return $this->memcache->get($key);
                }
        }

        /**
         * Delete data by key
         */
        function delete($key, $timeout = 0) {
                if (empty ($this->connected_servers)) {
                        return false;
                } else {
                        return $this->memcache->delete($key, $timeout);
                }
        }
		
		function increment($key)
		{
			  if (empty ($this->connected_servers)) {
                        return false;
                } else {
                        return $this->memcache->increment($key, 1);
                }

		}
		
		function decrement($key)
		{

			if (empty ($this->connected_servers)) {
                        return false;
                } else {
                        return $this->memcache->decrement($key, 1);
                }
		}

}
		
?>