using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Models
{
    public class ApiRes
    {

        public bool Success { get; set; }
        public string Content { get; set; }
        public string Error { get; set; } = string.Empty;

        public User User { get; set; }
        
        public List<User> Users { get; set; }

        

    }
}
