using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Models
{
    public class User
    {
        [Key]
        public string Id { get; set; }

        [Column("first_name")]
        public string FirstName { get; set; }

        [Column("last_name")]
        public string LastName { get; set; }

        [Column("email")]
        public string Email { get; set; }

        [Column("password")]
        public string Password { get; set; }

        [Column("phone")]
        public string Phone { get; set; }

        [Column("birth_date")]
        public DateTime BirthDate { get; set; }

        [Column("nationality")]
        public string Nationality { get; set; }

        [Column("admin")]
        public bool Admin { get; set; }

    }
}
