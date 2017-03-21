using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
   public class CState
    {
        public Guid Id { get; set; }
        public string StateName { get; set; }

        public CState()  {}

        public CState(Guid id, string stateName)
        {
            Id = id;
            StateName = stateName; 
        }


        public void Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                tblState s = new tblState();
                s.Id = Guid.NewGuid();
                s.StateName = StateName;

                oDC.tblStates.InsertOnSubmit(s);
                oDC.SubmitChanges(); 

            }
            catch(Exception ex)
            {
                throw ex; 
            }
        }


        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblState state = (from s in oDC.tblStates where s.Id == Id select s).FirstOrDefault();

                state.Id = Id;
                state.StateName = StateName; 

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblState state = (from s in oDC.tblStates where s.Id == Id select s).FirstOrDefault();

                oDC.tblStates.DeleteOnSubmit(state);
                oDC.SubmitChanges();
            }
        }
    }
}
