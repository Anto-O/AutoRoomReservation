using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Models
{
    public class Room
    {

        [Key]
        [Column("id")]
        public string Id { get; set; }

        [Column("number")]
        public string Number { get; set; }

        [Column("area")]
        public string Area { get; set; }

        [Column("price")]
        public string Price { get; set; }

        [Column("place")]
        public string Place { get; set; }

        [Column("apartment_id")]
        public string ApartmentId { get; set; }
        
        public Apartment apartment { get; set; }
    }
}
